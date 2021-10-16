function saveData(title, form, url) {
    var form_data = new FormData($('#'+form)[0]);

    swal({
        title: title,
        text: "Apa kamu yakin ?",
        icon: "info",
        buttons: true,
        primariMode: true,
    })
        .then((res) => {
            if (res) {
                $.ajax({
                    type: "POST",
                    data: form_data,
                    url: url,
                    async: true,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Accept': "application/json"
                    },
                    success: function (data, textStatus, xhr) {
                        console.log(data);

                        if (xhr.status === 200) {
                            swal("Data Updated ", {
                                icon: "success",
                                buttons: false,
                                timer: 1000
                            }).then((dat) => {
                                window.location.reload();
                            });
                        } else {
                            swal(data['msg'])
                        }
                        console.log(data);
                    },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status);
                        console.log(textStatus);
                    },
                    error: function (error, xhr, textStatus) {
                        // console.log("LOG ERROR", error.responseJSON.errors);
                        // console.log("LOG ERROR", error.responseJSON.errors[Object.keys(error.responseJSON.errors)[0]][0]);
                        console.log(xhr.status);
                        console.log(textStatus);
                        console.log(error.responseJSON);
                        swal(error.responseJSON['message'] ? error.responseJSON['message'] : error.responseJSON.errors ? error.responseJSON.errors[Object.keys(error.responseJSON.errors)[0]][0] : error.responseJSON['msg'] )

                    }
                })
            }
        });
    return false;
}

function deleteData(text, url) {
    var form_data = {
        '_token': '{{csrf_token()}}'
    }
console.log(url);
    swal({
        title: 'Hapus Data',
        text: "Apa kamu yakin menghapus data "+text+" ?",
        icon: "info",
        buttons: true,
        dangerMode: true,
    })
        .then((res) => {
            if (res) {
                $.ajax({
                    type: "GET",
                    data: form_data,
                    url: url,
                    async: true,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Accept': "application/json"
                    },
                    success: function (data, textStatus, xhr) {
                        console.log(data);

                        if (xhr.status === 200) {
                            swal("Data Updated ", {
                                icon: "success",
                            }).then((dat) => {
                                window.location.reload();
                            });
                        } else {
                            swal(data['msg'])
                        }
                        console.log(data);
                    },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status);
                        console.log(textStatus);
                    },
                    error: function (error, xhr, textStatus) {
                        // console.log("LOG ERROR", error.responseJSON.errors);
                        // console.log("LOG ERROR", error.responseJSON.errors[Object.keys(error.responseJSON.errors)[0]][0]);
                        console.log(xhr.status);
                        console.log(textStatus);
                        console.log(error.responseJSON);
                        swal(error.responseJSON['message'] ? error.responseJSON['message'] : error.responseJSON.errors ? error.responseJSON.errors[Object.keys(error.responseJSON.errors)[0]][0] : error.responseJSON['msg'] )

                    }
                })
            }
        });
    return false;
}
