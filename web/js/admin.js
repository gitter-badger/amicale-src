/**
 * Created by valentin on 2/5/16.
 */

$("#upload").change(function() {
    // will log a FileList object, view gifs below
    $("#button_name").text(this.files[0].name);
});