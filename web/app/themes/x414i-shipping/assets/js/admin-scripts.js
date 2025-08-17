document.addEventListener("DOMContentLoaded", function () {
  var editorWrap = document.querySelector(".wp-editor-wrap");
  var editorWrapBox = document.querySelector("#titlediv div.inside");

  if (editorWrap) {
    editorWrap.style.display = "none";
  }
  if (editorWrapBox) {
    editorWrapBox.style.display = "none";
  }
});
