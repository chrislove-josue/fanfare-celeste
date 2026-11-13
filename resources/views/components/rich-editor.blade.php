@props([
    'name',
    'id' => null,
    'value' => '',
    'rows' => 10,
    'placeholder' => '',
    'height' => 380,
])

@php
    $hasError = $errors->has($name);
@endphp

<textarea
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    data-rich-editor
    data-height="{{ $height }}"
    @class(['form-control', 'rich-editor', 'is-invalid' => $hasError])
>{{ old($name, $value) }}</textarea>

@if ($hasError)
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
@endif

@once
    @push('styles')
        <style>
            .rich-editor {
                min-height: 160px;
            }

            .tox-tinymce {
                border-radius: .375rem !important;
                border-color: #dee2e6;
            }

            .rich-editor.is-invalid + .tox-tinymce {
                border-color: #dc3545;
            }

            .tox .tox-toolbar__group {
                flex-wrap: wrap;
            }

            .tox .tox-editor-header {
                border-radius: .375rem .375rem 0 0;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
        <script>
            (function () {
                const uploadUrl = @json(route('admin.editor.upload'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

                function initRichEditors() {
                    if (!window.tinymce || !document.querySelector('textarea[data-rich-editor]')) {
                        return;
                    }

                    document.querySelectorAll('textarea[data-rich-editor]').forEach(function (element) {
                        tinymce.init({
                            target: element,
                            license_key: 'gpl',
                            language: 'fr_FR',
                            language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@26.11.2/langs7/fr_FR.js',
                            height: parseInt(element.dataset.height || '380', 10),
                            menubar: 'edit view insert format tools table help',
                            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount quickbars',
                            toolbar: [
                                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough',
                                'forecolor backcolor | alignleft aligncenter alignright alignjustify',
                                'bullist numlist outdent indent | link image media table | removeformat code fullscreen'
                            ].join(' | '),
                            toolbar_mode: 'wrap',
                            font_family_formats: 'Inter=Inter,system-ui,-apple-system,Segoe UI,sans-serif; Arial=Arial,Helvetica,sans-serif; Georgia=Georgia,serif; Times New Roman=Times New Roman,Times,serif; Verdana=Verdana,sans-serif; Courier New=Courier New,monospace',
                            font_size_formats: '12px 14px 16px 18px 20px 24px 28px 32px 36px 48px',
                            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
                            quickbars_insert_toolbar: false,
                            branding: false,
                            promotion: false,
                            convert_urls: false,
                            automatic_uploads: true,
                            content_style: "body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', sans-serif; font-size: 15px; line-height: 1.7; color: #1a1a2e; }",
                            images_upload_handler: function (blobInfo, progress) {
                                return new Promise(function (resolve, reject) {
                                    const formData = new FormData();
                                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                                    fetch(uploadUrl, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        credentials: 'same-origin',
                                        body: formData
                                    })
                                        .then(function (response) {
                                            return response.json().then(function (data) {
                                                if (!response.ok || !data.location) {
                                                    throw new Error(data.message || data.error || 'Échec du téléversement de l\'image.');
                                                }

                                                return data;
                                            });
                                        })
                                        .then(function (data) {
                                            resolve(data.location);
                                        })
                                        .catch(function (error) {
                                            reject(error.message || 'Échec du téléversement de l\'image.');
                                        });
                                });
                            },
                            setup: function (editor) {
                                editor.on('change', function () {
                                    editor.save();
                                });
                            }
                        });
                    });

                    document.addEventListener('submit', function () {
                        if (window.tinymce) {
                            tinymce.triggerSave();
                        }
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initRichEditors);
                } else {
                    initRichEditors();
                }
            })();
        </script>
    @endpush
@endonce
