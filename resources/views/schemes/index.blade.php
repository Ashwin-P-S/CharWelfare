@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="container">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="container d-flex justify-content-between">
        <div>
            <a href="{{ url('/') }}" class="btn btn-dark">
                {{ __('Back') }}
            </a>
        </div>
        <div class="mt-2">
            <p style="color: #1F2544; font-weight: bolder; font-size: 2rem;"> All Available Schemes </p>
        </div>
        <div>
            <a href="{{ route('schemes.add') }}" class="btn btn-dark">
                {{ __('Add New Scheme') }}
            </a>
        </div>
    </div>

    <div class="container">
        <div class="d-flex gap-3 justify-content-end">
            <div class="fw-bolder fs-5 d-flex align-items-center">
                Select type of disability:
            </div>
            <select name="disability" id="disability" onchange="searchFilter()" class="form-select w-25">
                <option value="All">All - {{ $schemes->count() }} Schemes</option>
                @foreach($types as $type)
                    <option value="{{ $type->disability }}">
                        {{ $type->disability }} - {{ $type->count }} Schemes
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="container my-5">
        <table id="schemesTable">
            <thead>
            <tr>
                <th>Scheme Name</th>
                <th>Type of Disability</th>
                <th>Description</th>
                <th>How to Apply</th>
                <th colspan="2" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            @foreach ($schemes as $scheme)
                <tr>
                    <td>{{ $scheme->name }}</td>
                    <td>{{ $scheme->disability }}</td>
                    <td>{{ $scheme->description }}</td>
                    <td>
                            <?php echo $scheme->how_to_apply; ?>
                    </td>
                    <td>
                        <input type="button" value="Delete" onclick="showWarning({{ $scheme->id }});"
                               class="btn btn-outline-danger">
                    </td>
                    <td>
                        <a href="{{ route('schemes.modify', [$scheme->id]) }}"
                           class="btn btn-outline-primary">Modify</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const showWarning = (id) => {
            url = "/scheme/delete/" + id;
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: `<a href='${url}' style='text-decoration:none; color:#fff;'>Delete</a>`,
                confirmButtonColor: "red",
                cancelButtonColor: "#0B61DF"
            });
        }

        const updateSchemesTable = (schemes, disability) => {
            // Get the Table Body
            const tableBody = $('.table-group-divider');
            tableBody.empty(); // Clears the Table

            schemes.forEach(function (scheme) {
                const row = '<tr>' +
                    '<td>' + scheme.name + '</td>' +
                    '<td>' + scheme.disability + '</td>' +
                    '<td>' + scheme.description + '</td>' +
                    '<td>' + scheme.how_to_apply + '</td>' +
                    '<td><input type="button" value="Delete" onclick="showWarning(' + scheme.id + ');" class="btn btn-outline-danger"></td>' +
                    '<td><a href="/scheme/edit/' + scheme.id + '" class="btn btn-outline-primary">Modify</a></td>' +
                    '</tr>';
                tableBody.append(row);
            });
        }

        const searchFilter = () => {
            const disability = $('#disability').val();

            $.ajax({
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "disability": disability,
                },
                url: "{{ route("schemes.search") }}",
                success: (schemes) => {
                    updateSchemesTable(schemes, disability);
                },
                errors: (err) => {
                    console.log(err);
                }
            });
        }
    </script>
@endsection
