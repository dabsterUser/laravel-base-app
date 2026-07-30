<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Get list of discoverable models from the app/Models directory.
     */
    protected function getDiscoverableModules(): array
    {
        $modelsPath = app_path('Models');
        $modules = [];

        if (file_exists($modelsPath)) {
            $files = scandir($modelsPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || is_dir($modelsPath . '/' . $file)) {
                    continue;
                }

                $className = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    if ($reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) && !$reflection->isAbstract()) {
                        $modelInstance = new $className();
                        $tableName = $modelInstance->getTable();

                        // Create friendly labels
                        $label = Str::headline(class_basename($className));

                        $modules[class_basename($className)] = [
                            'class' => $className,
                            'label' => $label,
                            'table' => $tableName,
                        ];
                    }
                }
            }
        }

        return $modules;
    }

    /**
     * Display the report selection dashboard.
     */
    public function index()
    {
        $modules = $this->getDiscoverableModules();
        return view('admin.reports.index', compact('modules'));
    }

    /**
     * AJAX endpoint to fetch column lists for a specific model.
     */
    public function getColumns(Request $request)
    {
        $modelName = $request->query('model');
        $modules = $this->getDiscoverableModules();

        if (!isset($modules[$modelName])) {
            return response()->json(['error' => 'Invalid module / model name.'], 400);
        }

        $module = $modules[$modelName];
        $table = $module['table'];

        // Get actual schema column names
        $columns = Schema::getColumnListing($table);

        // Security filter: Exclude sensitive columns
        $sensitiveColumns = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'api_token'];
        $filteredColumns = array_values(array_filter($columns, function ($col) use ($sensitiveColumns) {
            return !in_array(strtolower($col), $sensitiveColumns);
        }));

        return response()->json([
            'columns' => $filteredColumns,
            'table' => $table
        ]);
    }

    /**
     * Export selected columns of a model based on requested parameters and filters.
     */
    public function export(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'columns' => 'required|array',
            'columns.*' => 'string',
            'format' => 'required|in:csv,print',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $modelName = $request->input('model');
        $selectedColumns = $request->input('columns');
        $format = $request->input('format');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $modules = $this->getDiscoverableModules();
        if (!isset($modules[$modelName])) {
            abort(400, 'Invalid module selected.');
        }

        $module = $modules[$modelName];
        $className = $module['class'];
        $modelInstance = new $className();
        $table = $module['table'];

        // Build the dynamic Eloquent query
        $query = $className::query();

        // 1. Security Check: Since TenantScope is globally registered via BelongsToTenant,
        // it will automatically restrict the results to the authenticated user's tenant if applicable!
        // We can double-verify that if a tenant_id column exists, and we have an authenticated user with a tenant,
        // and for any reason the global scope was bypassed, we apply it.
        if (Schema::hasColumn($table, 'tenant_id') && auth()->user() && auth()->user()->tenant_id) {
            $query->where($table . '.tenant_id', auth()->user()->tenant_id);
        }

        // 2. Dynamic Date Range Filters
        if ($startDate && Schema::hasColumn($table, 'created_at')) {
            $query->whereDate($table . '.created_at', '>=', $startDate);
        }
        if ($endDate && Schema::hasColumn($table, 'created_at')) {
            $query->whereDate($table . '.created_at', '<=', $endDate);
        }

        // Retrieve dataset
        $data = $query->latest()->get();

        // Format and generate the selected headers
        $headersList = array_map(function ($col) {
            return Str::headline($col);
        }, $selectedColumns);

        // Export format 1: Excel-Compatible CSV Download
        if ($format === 'csv') {
            $fileName = Str::snake($module['label']) . '_report_' . date('Ymd_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($data, $selectedColumns, $headersList) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Put headers
                fputcsv($file, $headersList);

                // Put rows
                foreach ($data as $row) {
                    $rowData = [];
                    foreach ($selectedColumns as $column) {
                        $val = $row->{$column};

                        // Handle array or object values (such as JSON data fields)
                        if (is_array($val) || is_object($val)) {
                            $val = json_encode($val, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        }

                        $rowData[] = $val;
                    }
                    fputcsv($file, $rowData);
                }

                fclose($file);
            };

            return new StreamedResponse($callback, 200, $headers);
        }

        // Export format 2: Premium HTML Printable View
        return view('admin.reports.print', [
            'label' => $module['label'],
            'headers' => $headersList,
            'columns' => $selectedColumns,
            'data' => $data,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
    }
}
