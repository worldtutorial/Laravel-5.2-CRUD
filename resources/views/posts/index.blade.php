@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Post List') }}</div>

                    <div class="card-body">

                        @if (session('message_success'))
                            <div class="alert alert-success">
                                {{ session('message_success') }}
                            </div>
                        @elseif (session('message_error'))
                            <div class="alert alert-danger">
                                {{ session('message_error') }}
                            </div>
                        @endif
                        <form class="form-inline" action="{{ url('admin/post') }}" method="get">
                            <input type="search" class="form-control" id="search"
                                   placeholder="Enter title & description" name="search"
                                   value="{{ (isset($_GET['search']) && !empty($_GET['search']))?$_GET['search']:"" }}">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($posts) && count($posts))
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post['title'] }}</td>
                                        <td>{{ $post['description'] }}</td>
                                        <td>
                                            @if(isset($post['deleted_at']) && !empty($post['deleted_at']))
                                                <a href="{{ url('admin/post/restore/'.$post['id']) }}" class="btn btn-info">Restore</a>
                                            @else
                                                <a href="{{ url('admin/post/'.$post['id'].'/edit') }}">Edit</a>

                                                <form class="form-inline" action="{{ url('admin/post/'.$post['id']) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-primary">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection