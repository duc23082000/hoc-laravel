@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('lesson.addData') }}" method="POST" enctype="multipart/form-data">
        {{-- Tên lesson  --}}
        <div class="form-group">
            <label for="name">{{ __('content.name') }}:</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') }}" placeholder="{{ __('content.name') }}...">
        </div>
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Lựa chọn Courses  --}}
        <div class="form-floating" style="width: 30%;">
            <select class="form-select" aria-label="Floating label select example"
                    id="floatingSelect" name="course">
              <option selected value="">Chọn dữ liệu</option>
            
              @foreach ($courselist as $item)
                <option value="{{ $item->id }}" @if($item->id == old('course')) selected @endif>{{ $item->course_name }}</option>
              @endforeach

            </select>
            <label for="floatingSelect">{{ __('content.course') }}:</label>
        </div>
        @error('course')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Trạng thái lesson --}}
        <div>
            {{ __('content.status') }}:
            @foreach ($enum as $key => $value)
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}" @if($value == old('status') || $value == 0)checked @endif > {{ __('content.lessonStatus.' .$key) }}
            @endforeach
        </div>
        @error('status')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Nội dung Lesson  --}}
        <label for="content">{{ __('content.description') }}:</label>
	    <textarea id="content" name="content" rows="10">@if (session('content')) {{ session('content') }}@else{{ old('content') }}@endif</textarea>
        
        {{--  --}}
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Text areas<small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Settings 1</a>
                                <a class="dropdown-item" href="#">Settings 2</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="alerts"></div>
                    <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-edit="fontSize 5">
                                        <p style="font-size:17px">Huge</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 3">
                                        <p style="font-size:14px">Normal</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 1">
                                        <p style="font-size:11px">Small</p>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                            <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                            <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                            <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                            <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                            <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                            <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                            <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                            <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                            <div class="dropdown-menu input-append">
                                <input class="span2" placeholder="URL" type="text" data-edit="createLink">
                                <button class="btn" type="button">Add</button>
                            </div>
                            <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" title="Insert picture (or just drag &amp; drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                            <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage">
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                        </div>
                    </div>

                    <div id="editor-one" class="editor-wrapper placeholderText" contenteditable="true"></div>

                    <textarea name="descr" id="descr" style="display:none;">12323123213</textarea>

                    <br>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 ">Resizable Text area</label>
                        <div class="col-md-9 col-sm-9 ">
                            <textarea class="resizable_textarea form-control" placeholder="This text area automatically resizes its height as you fill in more text courtesy of autosize-master it out..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  --}}

        {{-- Video lesson  --}}
        <div class="mb-3">
            <label for="video">{{ __('content.image') }}:</label>
            <input class="form-control form-control-sm" type="file" name="video" id="video">
        </div>
        @error('video')
            <p style="color: red">{{ $message }}</p>
        @enderror

        @csrf
        <button type="submit" class="btn btn-primary">{{ __('content.add') }}</button>
        <a href="{{ route('courses.list') }}" class="btn btn-secondary">{{ __('content.back') }}</a>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
	tinymce.init({
	selector: '#content'
	});
</script>
@endsection