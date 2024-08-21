@extends('layouts.appLayout')
@section('viewTitle')
    
    Manage Users
@endsection
@section('main')
<div class="row">
    <div class="col-12">
      <div>
        @if (session('error'))
        <div  class="alert alert-warning text-white py-2">
          {{session('error')}}
        </div>
            
        @endif
      </div>
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>All Users</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead style="background-color: rgba(22,22,24,.12)">
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7"><span class="me-4">Id</span> Author</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Roles</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Log Status</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Joined At</th>
                  <th class="text-secondary opacity-7">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $item)
                <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class=" me-3 d-flex align-items-center ps-2" style="min-width: 30px">
                            <strong>{{$item->id}}</strong>
                        </div>
                        <div>
                          <img src="{{$item->avatar_url}}" class="avatar avatar-sm me-3 object-fit-cover" alt="user1">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{$item->full_name}}</h6>
                          <p class="text-xs text-secondary mb-0">{{$item->email}}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      {{-- <p class="text-xs font-weight-bold mb-0">Manager</p> --}}
                      <p class="text-xs text-secondary mb-0 pt-1 d-flex gap-1" style="flex-wrap: wrap">
                        @foreach ($item->roles as $role)
                            <span class=" border rounded-2 p-1">{{$role}}</span>
                        @endforeach
                      </p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      @if ($item->blocked_until == '')
                      
                      <span class="badge badge-sm bg-gradient-success">Actived</span>
                      @else
                      <span class="badge badge-sm bg-gradient-danger">Blocked</span>
                          
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{explode(' ',$item->created_at)[0]}}</span>
                    </td>
                    <td class="align-middle ">
                      <a href="/users/{{$item->uuid}}" class="text-white font-weight-bold  text-xs mb-0 btn btn-sm btn-primary ms-2 py-2 px-3" data-toggle="tooltip" data-original-title="Edit user">
                        View
                      </a>
                    </td>
                  </tr>
                @endforeach
                
               
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection