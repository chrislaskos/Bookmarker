function getXMLHTTPRequest() 
{
    var request = false;
    try {
        /* for Firefox */
        request = new XMLHttpRequest();
    } catch (error) {
        try {
            /* for some versions of IE */
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (error) {
            try {
                /* for some older versions of IE */
                request = new ActiveXObject("Mircrosoft.XMLHTTP");
            } catch (error) {
                request = false;
            }
        }
    }
    
    return request;
}

function addNewBookmark() 
{
    var url = "insert_bookmark.php";
    // create a pair of name/value from the form field name
    // and the value added by the user
    var params = "new_url=" + encodeURI(document.getElementById('new_url').value);
    myRequest.open("POST", url, true);
    myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // https://fetch.spec.whatwg.org/#forbidden-header-name
    // forbidden header names
    /*myRequest.setRequestHeader("Content-length", params.length);
    myRequest.setRequestHeader("Connection", "close");*/
    myRequest.onreadystatechange = addBookmarkResponse;
    // send to PHP script
    myRequest.send(params);
}

function addBookmarkResponse() 
{
    // check the state of the object
    // code 4 means completed
    if (myRequest.readyState == 4) {
        if (myRequest.status == 200) {
            // this will be displayed in a div with id="displayresult"
            result = myRequest.responseText;
            document.getElementById('displayresult').innerHTML = result;
        } else {
            alert('There was a problem with the request.');
        }
    } else {
        document.getElementById('displayresult').innerHTML = '<img src="img/ajax-loader.gif"/>';
    }
}

function confirmDeletion(form)
{
    var dialog = confirm("Are you sure?");
    if (dialog == true) {
        // Pressed OK
        // form must be submitted
        form.submit();
    } else {
        // Pressed Cancel
        // do nothing
    }
}
