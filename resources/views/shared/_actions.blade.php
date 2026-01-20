<style type="text/css">
.swal-button {
    margin-right: 0px!important;
}
.swal-footer {
    padding: 13px 140px!important;
    }
.btn {
        padding: 10px;
    }
</style>
<script type="text/javascript">
    function delete_function(id) {
        swal({
            title: "Warning!",
            text: "Are you sure wanted to remove it?", 
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: false,
                    visible: true,
                    className: "button button-primary",
                    closeModal: true,
                },
                confirm: {
                    text: "Ok",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                }
            },
        }).then((willDelete) => {
            if (willDelete) {
                $("#delete_frm_"+id).submit();
            } 
        });
}
</script>

@if(Auth::user()->id==1 || $role=="Sub Admin")
    <a style="color: #fff;" href="{{ route($entity.'.edit', [str_singular($entity) => $id])  }}" class="btn btn-info"><i class="mdi mdi-edit"></i> Edit </a>
@endif

@if(Auth::user()->id==1 || $role=="Sub Admin")
@if($entity!='subadmin')

{{ html()->form('delete')->route($entity.'.destroy', ['user' => $id])->id('delete_frm_'.$id)->open() }}

        <button style="color: #fff;"  onclick="delete_function({{$id}})" id="deletebtn" type="button" class="btn btn-danger"><i class="mdi mdi-trash"></i> Delete</button>
    {{ html()->form()->close() }}

@else

<form method="POST" action="{{url('/')}}/{{$entity}}/{{$id}}/destroy" id="delete_frm_{{$id}}">
    @csrf

        <button style="color: #fff;"  onclick="delete_function({{$id}})" id="deletebtn" type="button" class="btn btn-danger"><i class="mdi mdi-trash"></i> Delete</button>

    </form>

@endif 

@endif 



