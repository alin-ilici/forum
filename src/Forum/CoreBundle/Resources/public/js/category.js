$(document).ready(function() {
    $('body').on('click', '#newSubcategoryButton', function() {
        $('#addSubcategoryModal').modal('show');
    });

    $('body').on('click', '#editCategoryButton', function() {
        $('#editCategoryModal').modal('show');
    })
});