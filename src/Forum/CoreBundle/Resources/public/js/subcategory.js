$(document).ready(function($) {
    // new topic modal
    $('#newOrEditTopicNameButton').on('click', function() {
        $('#newTopicModal').modal('show');
    });

    // edit subcategory modal
    $('body').on('click', '#editSubcategoryButton', function() {
        $('#editSubcategoryModal').modal('show');
    })
});