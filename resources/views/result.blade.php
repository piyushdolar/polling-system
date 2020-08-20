@extends('layouts.app')
@section('content')
<div class="container">
    <div class='card'>
        <div class='card-body'>
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

<script type="text/javascript">
    $(document).ready(function(){
        // DataTable function
        var table = $('#sampleTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("answer.result") }}',
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
    })
</script>
@endsection
