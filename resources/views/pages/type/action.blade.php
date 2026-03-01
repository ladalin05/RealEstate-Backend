
<div class="d-flex gap-2">
    <a href="{{route('type.edit', $row->id)}}" class="btn btn-sm btn-success">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger data_remove"
        data-id="{{$row->id}}">
        <i class="fa fa-trash"></i>
    </button>
</div>

<script>
    // DELETE
    $(document).on("click",".data_remove", function(){

        let id = $(this).data("id");

        Swal.fire({
            title: '{{ __("global.dlt_warning") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("global.dlt_confirm") }}'
        }).then((result) => {

            if(result.isConfirmed){

                $.post("{{ URL::to('admin/ajax_delete') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    action_for: "type_delete"
                }, function(res){

                    if(res.status == '1'){
                        $('#type-table').DataTable().ajax.reload();
                        Swal.fire('Deleted!', '', 'success');
                    }
                });

            }
        });
    });
</script>