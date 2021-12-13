let form = document.querySelector('#form');
let file_input = document.querySelector('#file_data');
let response;

window.onload = () => {
    form.onsubmit = function(evt) {
        if(file_input.files.length == 0) {
            evt.preventDefault();
            return false;
        }
        console.log('mais foi');
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = (evt) => {
            const request = evt.currentTarget;
            if(request.readyState == 4 && request.status == 200) {
                response = JSON.parse(request.responseText);
                form.action = response.body.upload.upload_link;
                // new_form_div.innerHTML = response.body.upload.form;
                form.submit();
            }
        }
        xhttp.open('GET', 'function_vimeo.php', true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send('file_size=' + file_input.files[0].size.toString());
        evt.preventDefault();
    }
}