@extends('layouts.app')
@section('content')
<div class="container">
    <div class='card'>
        <div class='card-body'>
            <!-- Create Question -->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createQuestion">
                        Create Question
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="createQuestion" tabindex="-1" aria-labelledby="createQuestionLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('question.create') }}" method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createQuestionLabel">What is your question?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="questionInput">Enter Question</label>
                                        <input type="text" class="form-control" name='question' id="questionInput" placeholder="What is the color of your car?">
                                    </div>
                                    <label for="answerInput">Enter Answer</label>
                                    <div class="form-group">
                                        <div class='row'>
                                            <div class='col-md-9'>
                                                <input type="text" class="form-control mb-2 answerInputBox" name='answer[]' id="answerInput" placeholder="Enter the answer">
                                            </div>
                                            <div class='col-md-3'>
                                                <button type="button" class='btn btn-primary plus'><i class='fa fa-plus'></i></button>
                                                <button type="button" class='btn btn-danger minus'><i class='fa fa-minus'></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create Question</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>


            <!-- Listing Question which are created. -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="table-striped table-responsive">
                        <table class="table table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Question</td>
                                    <td>Answers</td>
                                    <td>Created</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JQuery -->
<script type="text/javascript">
    $(document).ready(function() {

        // Create modal events
        $('.plus').on('click',function(){
            if($('.answerInputBox').length < 5){
                $this = $(this);
                var $div = $this.closest('.row').find('.col-md-9 > input:first').clone();
                $this.closest('.row').find('.col-md-9').append($div.val(''));
            }else{
                alert('Can not add more than 10 answer.');
                return false;
            }
        })
        $('.minus').on('click',function(){
            $this = $(this);
            var $div = $this.closest('.row').find('.col-md-9 > input:last').remove();
        })

        // DataTable function
        var table = $('#sampleTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("question.show") }}',
            columns: [
                { defaultContent: '' },
                { data: 'question', name: 'question' },
                { data: 'answer', name: 'answer' },
                { data: 'created_at', name: 'created_at' }
            ]
        });
        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endsection
