<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">Usuarios</h1>
                <p class="text-muted mb-0">Búsqueda, filtros y administración de roles.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al panel
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nombre o email">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Rol</label>
                        <select name="role" class="form-select">
                            <option value="">Todos</option>
                            <option value="admin" @selected($role === 'admin')>Admin</option>
                            <option value="artist" @selected($role === 'artist')>Artista</option>
                            <option value="visitor" @selected($role === 'visitor')>Visitante</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="active" @selected($status === 'active')>Activo</option>
                            <option value="inactive" @selected($status === 'inactive')>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        @if($user->avatar_path)
                                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="text-primary fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">{{ $user->user_type }}</span>
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                            Ver
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No hay usuarios con los filtros seleccionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-primary {
            background-color: #6b21a8 !important;
            border-color: #6b21a8 !important;
        }
        .text-primary {
            color: #6b21a8 !important;
        }
    </style>
</x-app-layout>
