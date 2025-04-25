function search_npk(npk) {
    if ('URLSearchParams' in window) {
        console.log(npk);
        window.location.replace("actions/search-npk.php?q=" + npk.toString())
    }
}

$(function() {
    $('document').ready(function(){
        var input = document.getElementById("npk-search");

        // Execute a function when the user presses a key on the keyboard
        input.addEventListener("keypress", function(event) {
        // If the user presses the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("npk-search-btn").click();
        }
        });
    });

    $('#npk-search-btn').click(function(){
        var searched_npk = $("#npk-search").val();
        search_npk(searched_npk);
    });
});
