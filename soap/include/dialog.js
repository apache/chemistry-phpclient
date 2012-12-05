function showModal(divId, notice, objectId) {
    window.onscroll = function () {
        document.getElementById(divId).style.top = document.body.scrollTop;
    };
    
    var elements, element;
    var i;
    var dialogNode = document.getElementById(divId);
    
    // Set the notice text into the dialognotice DIV element
    if (notice != null) {
        elements = dialogNode.getElementsByTagName('div');
        for (i=0; i<elements.length; i++) {
            element = elements[i];
            if (element.className == 'dialognotice') {
                if (element.hasChildNodes()) {
                    while (element.childNodes.length >= 1) {
                        element.removeChild(element.firstChild);
                    }
                }
                element.appendChild(document.createTextNode(notice));
            }
        }
    }
    
    // Add objectid as hidden input parameter into form
    if (objectId != null) {
        var formElements = dialogNode.getElementsByTagName('form');
        var formElement = formElements[0]; // assumes single form in dialog
        var inputElements = formElement.getElementsByTagName('input');
        for (i=0; i<inputElements.length; i++) {
            var inputElement = inputElements[i];
            if (inputElement.getAttribute('name') == 'objectid') {
                element.parentNode.removeChild(inputElement);
            }
        }
    
        var objectParam = document.createElement('input');
        objectParam.setAttribute('type', 'hidden');
        objectParam.setAttribute('name', 'objectid');
        objectParam.setAttribute('value', objectId);
        formElement.appendChild(objectParam);
    }
    
    // show dialog
    document.getElementById(divId).style.display = "block";
    document.getElementById(divId).style.top = document.body.scrollTop;    
}

function hideModal(divID) {
    
    // hide dialog
    document.getElementById(divID).style.display = "none";
}
