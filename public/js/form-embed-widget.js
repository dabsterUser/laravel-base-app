(function() {
    // Find our script tag
    var scripts = document.getElementsByTagName('script');
    var currentScript = null;

    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf('form-embed-widget.js') !== -1 && scripts[i].getAttribute('data-form-id')) {
            currentScript = scripts[i];
            break;
        }
    }

    if (!currentScript) {
        return;
    }

    var formId = currentScript.getAttribute('data-form-id');
    var baseUrl = window.location.protocol + '//' + window.location.host;

    // Create iframe
    var iframe = document.createElement('iframe');
    iframe.src = baseUrl + '/f/' + formId;
    iframe.width = '100%';
    iframe.height = '650px';
    iframe.style.border = 'none';
    iframe.style.overflow = 'hidden';
    iframe.setAttribute('scrolling', 'no');

    // Insert iframe before currentScript
    currentScript.parentNode.insertBefore(iframe, currentScript);
})();
