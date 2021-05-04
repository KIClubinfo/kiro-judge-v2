function popUp(errors){

    var frog = window.open("","wildebeast","width=300,height=300,scrollbars=1,resizable=1")

    var html = "<html><head></head><body>"
    html += errors;
    html += "<body/>"

//variable name of window must be included for all three of the following methods so that
//javascript knows not to write the string to this window, but instead to the new window

    frog.document.open()
    frog.document.write(html)
    frog.document.close()

}