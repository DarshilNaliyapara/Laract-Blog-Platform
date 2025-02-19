import ajaxform from './custom.js';
$(document).ready(function () {
    $(".comment-btn, .comment, .postcomments").hide();

    $(".postContent").each(function () {
        let text = $(this).data('postvalue');
        console.log(text);
        let linkedText = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" class="text-blue-500 underline">$1</a>');
        $(this).html(linkedText);
    });

    $(".blogbtn").click(function (e) {
        e.preventDefault();
        $(this).prop("disabled", true).html("Processing...");
        const form = document.getElementById("postform");
        let inputData = new FormData(form);
        let blogId = $(".blogbtn").data("blogid");
        if (blogId) {
            inputData.append("_method", "PATCH");
            inputData.append("blog_id", blogId);
        }
        let url = blogId ? `/blogs/${blogId}` : "/blogs";

        $.ajax({
            url: url,
            method: "POST",
            data: inputData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: blogId ? "Post updated Successfully!" : "Post created Successfully!",
                    showConfirmButton: false,
                });
                console.log(response.message);
                $(".blogbtn").prop("disabled", false).html("Submit");
                $("#postform").trigger("reset");
                window.location.href = "/";
            },
            error: function (xhr) {
                Swal.fire({
                    title: "Error",
                    text: xhr.responseJSON?.message || "Something went wrong",
                    icon: "error",
                });
                console.log(xhr.responseJSON);
                $(".blogbtn").prop("disabled", false).html("Submit");
            },
        });
    });
    $(".show-comment-btn").click(function () {
        var commentSection = $(this).closest("form").find(".comment");
        var commentButton = $(this).closest("form").find(".comment-btn");
        var form = $(this).closest("form");
        var postComments = form.siblings(".postcomments");

        postComments.toggle();
        commentSection.toggle();
        commentButton.toggle();

        if (commentSection.is(":visible")) {
            $(this).html("Hide");
        } else {
            $(this).html("Comments");
        }
    });

    $("form#comment-form").submit(function (e) {
        e.preventDefault();

        var form = $(this);
        var commentInput = form.find("input[name='comment']").val();
        var commentId = form.find("input[name='comment']").data("commentid");
        form.find(".comment-btn").prop("disabled", true).html("Processing...");


        ajaxform('comments/', 'POST',
            {
                comment: commentInput,
                blog_id: commentId
            },
            function (response) {
                console.log(response.message);
                form.find("input[name='comment']").val("").hide();
                form.find(".comment-btn").hide();
                form.find(".show-comment-btn").html("Comment");

                location.reload();
            },
            function (xhr, status, error) {
                Swal.fire({
                    title: "Error",
                    text: "Field is Empty!!",
                    icon: "error"
                }).then(() => {
                    form.find(".comment-btn").prop("disabled", false).html("Comment");

                });
                console.error(xhr.responseText);

            }
        )
    });

    $(".dlt-btn").click(function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                var blogId = $(this).data('dltbtnid');
                ajaxform("blogs/" + blogId,
                    'POST',
                    { _method: "Delete" },

                    function (response) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Deleted Successfully!",
                            showConfirmButton: false,
                        });
                        location.reload();
                    },
                    function (xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                        console.error(xhr.responseText);

                    })

            }
        });
    });
    $(".cmtdltbtn").click(function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                var commentId = $(this).data('cmtdltbtnid');
                ajaxform("comments/" + commentId,
                    'POST',
                    {
                        _method: "DELETE"
                    },
                    function (response) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Deleted Successfully!",
                            showConfirmButton: false,
                        });
                        location.reload();
                    },
                    function (xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                    })

            }
        });
    });
    $(".setrolesbtn").click(function () {
        let row = $(this).closest("tr");
        let permissionsDiv = row.find(".permissions");
        permissionsDiv.show();

        let userId = row.find(".username").data("userid");
        let buttonRoleId = $(this).data("setroles");
        if (userId !== buttonRoleId) {
            console.log("Error: ID did not match.");
            return;
        }
        $(".setrolesbtn").click(function () {
            $(this).prop("type", "submit");

            $("form.set-role-form").on("submit", function (e) {
                e.preventDefault();
                $(".setrolesbtn").prop("disabled", true);

                let selectedRoles = $(this).find(".set-role:checked").map(function () {
                    return $(this).val();
                }).get();
                console.log(selectedRoles);
                let userData = $(this).find(".role-useremail").val();
                ajaxform("/users/set-roles-and-permission", "POST", {
                    user_email: userData,
                    roles: selectedRoles
                }, function (response) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Roles Added Successfully!",
                        showConfirmButton: false,
                    });
                    console.log("Success:" + response);
                    location.reload();
                }, function (xhr) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error While Adding Roles!",

                    });
                    console.log("Error:", xhr.responseJSON);
                });
            });

        });
    });

    $(".removerolesbtn").click(function () {
        let row = $(this).closest("tr");
        let permissionsDiv = row.find(".remove-role-form-group");
        permissionsDiv.show();

        let userId = row.find(".username").data("userid");
        let buttonRoleId = $(this).data("removeroles");
        if (userId !== buttonRoleId) {
            console.log("Error: ID did not match.");
            return;
        }
        $(".removerolesbtn").click(function () {
            $(this).prop("type", "submit");

            $("form.remove-role-form").on("submit", function (e) {
                e.preventDefault();
                $(".removerolesbtn").prop("disabled", true);

                let selectedRoles = $(this).find(".remove-role:checked").map(function () {
                    return $(this).val();
                }).get();
                console.log(selectedRoles);
                ajaxform("/users/remove/roles-and-permission", "POST",
                    {
                        user_id: userId,
                        roles: selectedRoles,

                    }, function (response) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Roles Removed Successfully!",
                            showConfirmButton: false,
                        });
                        console.log("Success:" + response);
                        location.reload();
                    }, function (xhr) {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error While Removing Roles!",

                        });
                        console.log("Error:", xhr.responseJSON);
                    });
            });

        });
    });


    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        console.log(value);
        $("#userroles_table tr ").filter(function () {
            $(this).toggle($(this).find(".useremail, .userroles").text().toLowerCase().indexOf(value) > -1)
        });
    });
});
