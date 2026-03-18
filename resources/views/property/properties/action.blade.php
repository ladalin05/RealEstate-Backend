
<div class="d-flex gap-2">
    <a
        href="{{route('property.properties.edit', ['id' => $row->id])}}"
        class="btn btn-success btn-sm text-white">
        <i class="ph ph-pencil-simple me-1"></i>
        Edit
    </a>

    <button type="button"
        class="btn btn-danger btn-sm data_remove"
        data-url="{{route('property.properties.deleted', ['id' => $row->id])}}"
        onclick="deleteData(event)">
        <i class="fa fa-trash"></i>
    </button>
</div>