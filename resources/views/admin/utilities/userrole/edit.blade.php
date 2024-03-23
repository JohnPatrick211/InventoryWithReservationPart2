@extends('admin.utilities.userrole.layout')

@section('content')

@php
    $page_title = Session::get('cms_name');
@endphp

<div class="content-header"></div>

<div class="page-header mb-3">
    <h3 class="mt-2" id="page-title">Update User Role</h3>
    <hr>
    <a href="{{ route('user-role.index') }}" class="btn btn-secondary btn-sm"><span
            class='fas fa-arrow-left'></span></a>
</div>

@if(count($errors) > 0)
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

@if(\Session::has('success'))
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> </h5>
                {{ \Session::get('success') }}
            </div>
        </div>
    </div>
@endif


<div class="row">

    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user-role.update',$userrole->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-5 col-md-3">
                            <label class="col-form-label">Description</label>
                            <input type="text" class="form-control" name="description" id="name"
                                value="{{ $userrole->ur_description }}" required>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="col-form-label">Status</label>
                            <select class="form-control" name="ur_is_active" id="access_level">
                                <option value="1"
                                    {{ $userrole->ur_is_active == 1 ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0"
                                    {{ $userrole->ur_is_active == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="col-form-label">List of Module</label>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-sm btn-success mr-2" id="btn-update-user-role">Save
                                changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
    </section>

    @endsection

    <script>
        //variables for my shopping list 


        // const myUL = document.getElementById("bold");
        let input = "";
        var button = "";
        var ul = "";
        var list = "";
        var trash = "";
        var btndelete = "";

        function addMenu() {
            input = $("#ui_menu :selected").text();
            button = document.getElementById("enter");
            ul = document.querySelector("ul");
            list = document.getElementsByTagName("li");
            trash = document.getElementsByClassName("btn");
            btndelete = document.getElementById("trash");

            addListAfterClick();

            alert(inputlength())
        }


        //For removing items with delete button
        Array.prototype.slice.call(trash).forEach(function (item) {
            item.addEventListener("click", function (e) {
                e.target.parentNode.remove()
            });
        })

        //loop for to strikeout the list 
        for (var i = 0; i < list.length; i++) {
            list[i].addEventListener("click", strikeout);

        }

        //toggle between classlist
        function strikeout() {
            this.classList.toggle("done");
        }

        //check the length of the string entered
        function inputlength() {
            return input.length;
        }

        //collect data that is inserted 
        function addli() {
            var li = document.createElement("li");
            var btn = document.createElement("button");
            btn.className = "btn";
            btn.innerHTML = "<i class=\"fas fa-trash\"></i>";
            btn.addEventListener("click", function (e) {
                e.target.parentNode.remove();
            });
            li.addEventListener("click", strikeout);
            li.innerHTML = input + "";
            li.appendChild(btn);
            ul.appendChild(li);
            input = "";

        }


        //this will add a new list item after click 
        function addListAfterClick() {
            if (inputlength() > 0) {
                addli();
            }

        }

    </script>
