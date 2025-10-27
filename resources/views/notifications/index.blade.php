@extends('dashboard.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notifications</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Message</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    <tr class="{{ $notification->read_at ? '' : 'table-light' }}">
                                        <td>{{ $notification->data['message'] }}</td>
                                        <td>
                                            @if(isset($notification->data['status']))
                                                @if($notification->data['status'] == 'Stock Bas')
                                                    <span class="badge bg-warning">
                                                        <i class="fa-solid fa-exclamation-triangle"></i> {{ $notification->data['status'] }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-{{ $notification->data['status'] == 'Validation' ? 'success' : ($notification->data['status'] == 'Refus' ? 'danger' : 'warning') }}">
                                                        {{ $notification->data['status'] }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if(isset($notification->data['view_url']))
                                                <a href="{{ $notification->data['view_url'] }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i> Voir
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Aucune notification trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection