@extends('layouts.app')
@section('content')
<form id="myForm" method="POST" action="#">
    @csrf
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<!-- Modal for confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Submission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Do you want to submit the form data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="yesButton">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#myForm').submit(function() {

            $(window).off('beforeunload');
        });

        $(window).on('beforeunload', function() {
            $('#confirmationModal').modal('show');
            return "You have unsaved changes.";
        });

        $('#yesButton').click(function() {
            $('#myForm').submit(); 
        });
    });
</script>
@endsection
