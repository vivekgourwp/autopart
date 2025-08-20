@extends('admin.layouts.layout')

@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
    select.form-select {
    padding: 5px 30px;
    border: 0;
    outline: 1px solid #CED4DA;
    color: #000000;
    padding-left: .5rem;
}
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">


              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="{{route('admin.addSubCategory')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Part</a>
                    <h4 class="card-title">Part Management</h4>
                    <p class="card-description"> Part List
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.bulkDeleteSubCategory') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <td><input type="checkbox" id="selectAll"></td>
                              <th> Sr no </th>
                              <th> Part Type Name </th>
                              {{-- <th> Part Type Arabic </th> --}}
                              <th> Part English</th>
                              <th> Part Arabic</th>
                              <th> Part Type French </th>
                              <th> Part Type Russian </th>
                              <th> Part Type Dari </th>
                              <th> Part Type Urdu </th>
                              <th> Status</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($type)
                            @php $i=1; @endphp
                            @foreach($type as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->category_name}} </td>
                              {{-- <td> {{$list->ar_category_name}} </td> --}}
                              <td> {{$list->subcat_name}} </td>
                              <td> {{$list->ar_subcat_name}} </td>
                              <td> {{$list->fr_subcat_name}} </td>
                              <td> {{$list->ru_subcat_name}} </td>
                              <td> {{$list->fa_subcat_name}} </td>
                              <td> {{$list->ur_subcat_name}} </td>
                              <td><select class="user_status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->is_active==0?'selected':''}}>Inactive</option>
                                  <option value="1" {{$list->is_active==1?'selected':''}}>Active</option>
                                </select>
                              </td>
                              <td>
                                <a href="{{route('admin.addSubCategory')}}/{{ $list->id }}"><i class="mdi mdi-lead-pencil"></i></a></td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" id="bulkDeleteBtn">Delete Selected</button>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        @endsection
        @push('scripts')

        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('error') }}",
                confirmButtonText: "OK"
              });
            });
        </script>
        @endif
        <script>


          $(document).ready( function () {
            var table = $('#example').DataTable( {
              "bPaginate": true,
              "bInfo": true,
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]]
            });
          } );


          $(document).ready(function () {
            $(document).on('change','.user_status',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/updateSubCategoryStatus')}}",
                type: "POST",
                datatype: "json",
                data: {
                  status: status,
                  user:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  Swal.fire({
                    title: "Success!",
                    text: "Part Status updated!",
                    icon: "success"
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

          });

        </script>

        <script>
          document.getElementById('selectAll').addEventListener('click', function (e) {
            let checkboxes = document.querySelectorAll('.selectBox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
          });

          document.getElementById('bulkDeleteBtn').addEventListener('click', function (e) {
            e.preventDefault(); // Stop normal form submit

            const form = document.getElementById('bulkDeleteForm');
            const checkboxes = document.querySelectorAll('.selectBox:checked');

            if (checkboxes.length === 0) {
              Swal.fire({
                icon: 'warning',
                title: 'No selection',
                text: 'Please select at least one Part to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected Part will be deleted.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete selected'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit(); // Submit the form only if confirmed
              }
            });
          });
        </script>
        @endpush
