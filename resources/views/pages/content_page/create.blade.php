<x-app-layout>
    <style>
        .card-box label {
            color: #343a40; /* Darker text for labels */
        }
    </style>
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Back button link --}}
                                <a href="{{ URL::to('admin/property') }}">
                                    <h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;">
                                        <i class="fa fa-arrow-left"></i> {{ __('global.back') }}
                                    </h4>
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('settings.content_page.create') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row mb-3">
                                <div class="col-md-6 mb-4 row align-items-center">
                                    <label for="title" class="form-label col-md-3 fw-bold">Title <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="title" name="title" class="form-control " required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4 row align-items-center">
                                    <label for="status" class="form-label col-md-3 fw-bold">Status</label>
                                    <div class="col-md-9">
                                        <select id="status" name="status" class="form-select form-select-lg">
                                            <option value="1" selected>Active</option>
                                            <option value="0">Disable</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>

                            <hr />
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">Page Sections</h4>
                                <button type="button" class="btn btn-success btn-sm px-3" id="addSection">
                                    <i class="bi bi-plus-lg"></i> + Add Section
                                </button>
                            </div>
                            <div class="card-body p-4" id="sectionArea">
                                <div class="section-item border p-4 mb-4 bg-light rounded-3">
                                    <h6 class="text-secondary mb-3">Section 1 (Default)</h6>
                                    <div class="row mb-4 ms-3">
                                        <label class="form-label col-3">Section Title</label>
                                        <div class="col-md-8">
                                            <input type="text" name="sections[0][title]" class="form-control" placeholder="e.g., Our Mission">
                                        </div>
                                    </div>

                                    <div class="row mb-4 ms-3">
                                        <label class="form-label col-3">Section Image</label>
                                        <div class="col-md-8">
                                            <input type="file" name="sections[0][image]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mb-4 ms-3">
                                        <label class="form-label col-3">Section Content</label>
                                        <div class="col-md-8">
                                            <textarea name="sections[0][content]" class="form-control elm1_editor" rows="4" placeholder="Enter section content here..."></textarea>
                                        </div>        
                                    </div>

                                    <div class="row mb-4 ms-3">
                                        <label class="form-label col-3">Status</label>
                                        <div class="col-md-8">
                                            <select name="sections[0][status]" class="form-select">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                                    <i class="bi bi-check-circle-fill"></i> Create Page
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: "textarea.elm1_editor",           
                height: 300,
                plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor",
                style_formats: [
                { title: 'Bold text', inline: 'b' },
                { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
                { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
                { title: 'Example 1', inline: 'span', classes: 'example1' },
                { title: 'Example 2', inline: 'span', classes: 'example2' },
                { title: 'Table styles' },
                { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
                ]
            });
        });

        let index = 1;
        document.getElementById('addSection').addEventListener('click', function() {
            const newSectionHtml = `
                <div class="section-item border p-4 mb-4 bg-light rounded-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-secondary mb-0">Section ${index + 1}</h6>
                        <button type="button" class="btn btn-danger btn-sm remove-section">
                            <i class="bi bi-trash-fill"></i> Remove
                        </button>
                    </div>

                    <div class="row mb-4 ms-3">
                        <label class="form-label col-3">Section Title</label>
                        <div class="col-md-8">
                            <input type="text" name="sections[${index}][title]" class="form-control" placeholder="e.g., Our Mission">
                        </div>
                    </div>

                    <div class="row mb-4 ms-3">
                        <label class="form-label col-3">Section Image</label>
                        <div class="col-md-8">
                            <input type="file" name="sections[${index}][image]" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-4 ms-3">
                        <label class="form-label col-3">Section Content</label>
                        <div class="col-md-8">
                            <textarea name="sections[${index}][content]" class="form-control" rows="4" placeholder="Enter section content here..."></textarea>
                        </div>
                    </div>

                    <div class="row mb-4 ms-3">
                        <label class="form-label col-3">Status</label>
                        <div class="col-md-8">
                            <select name="sections[${index}][status]" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('sectionArea').insertAdjacentHTML('beforeend', newSectionHtml);
            index++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-section') || e.target.closest('.remove-section')) {
                const sectionItem = e.target.closest('.section-item');
                if (sectionItem) {
                    sectionItem.remove();
                }
            }
        });
    </script>
</x-app-layout>
