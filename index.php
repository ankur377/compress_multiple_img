
<div id="root">
  <p>Upload an image and see the result</p>
  <input id="img-input"multiple type="file" accept="image/*" style="display:block" />
</div>
<a class="btn btn-sm btn-blocks btn-outline-primary downloadable" :download="output.name" :href="outputURL" title="Download the compressed image">Download</a>
<script type="text/javascript">
  const MAX_WIDTH = 1080;
const MAX_HEIGHT = 810;
const MIME_TYPE = "image/jpeg";
const QUALITY = 0.9;

const input = document.getElementById("img-input");
input.onchange = function (ev) {
  for(let j=0; j<=100; j++){
  const file = ev.target.files[j]; // get the file
  const blobURL = URL.createObjectURL(file);
  const img = new Image();
  img.src = blobURL;
  img.onerror = function () {
    URL.revokeObjectURL(this.src);
    // Handle the failure properly
    console.log("Cannot load image");
  };

  img.onload = function () {
    URL.revokeObjectURL(this.src);
    const [newWidth, newHeight] = calculateSize(img, MAX_WIDTH, MAX_HEIGHT);
    const canvas = document.createElement("canvas");
    canvas.width = newWidth;
    canvas.height = newHeight;
    const ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0, newWidth, newHeight);
    canvas.toBlob(
      (blob) => {
        // Handle the compressed image. es. upload or save in local state
        displayInfo('Original file', file);
        displayInfo('Compressed file', blob);
          setTimeout(function(){
              var a = document.createElement("a");
              document.body.appendChild(a);
              a.style = "display: none";
              var url = window.URL.createObjectURL(blob);
              a.href = url;
              a.download = "abc.png";
              a.click();
              window.URL.revokeObjectURL(url);

          },2000);
      },
      MIME_TYPE,
      QUALITY
    );
    document.getElementById("root").append(canvas);
  };
}};

function calculateSize(img, maxWidth, maxHeight) {
  let width = img.width;
  let height = img.height;

  // calculate the width and height, constraining the proportions
  if (width > height) {
    if (width > maxWidth) {
      height = Math.round((height * maxWidth) / width);
      width = maxWidth;
    }
  } else {
    if (height > maxHeight) {
      width = Math.round((width * maxHeight) / height);
      height = maxHeight;
    }
  }
  return [width, height];
}

// Utility functions for demo purpose

function displayInfo(label, file) {
  const p = document.createElement('p');
  p.innerText = `${label} - ${readableBytes(file.size)}`;
  document.getElementById('root').append(p);
}

function readableBytes(bytes) {
  const i = Math.floor(Math.log(bytes) / Math.log(1024)),
    sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

  return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
}
</script>