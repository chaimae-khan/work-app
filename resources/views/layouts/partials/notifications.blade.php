<!-- resources/views/layouts/partials/notifications.blade.php -->
<li class="dropdown notification-list topbar-dropdown">
    <a class="nav-link dropdown-toggle nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" onclick="resetNotificationCount()">
        <i data-feather="bell" class="noti-icon"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger rounded-circle noti-icon-badge" id="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
        <!-- item-->
        <div class="dropdown-item noti-title">
            <h5 class="m-0">
                <span class="float-end">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm p-0 text-dark">
                                <small>Tout marquer comme lu</small>
                            </button>
                        </form>
                    @endif
                </span>Notifications
            </h5>
        </div>

        <div class="noti-scroll" data-simplebar>
            @forelse(auth()->user()->notifications()->latest()->get() as $notification)
                <a href="{{ $notification->data['view_url'] ?? '#' }}" class="dropdown-item notify-item">
                    <div class="notify-icon bg-primary">
                        <i class="mdi mdi-comment-account-outline"></i>
                    </div>
                    <p class="notify-details">
                        {{ $notification->data['message'] }}
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </p>
                    
                    <div class="d-flex justify-content-end mt-1">
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="ms-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-primary p-0">
                                    <i class="mdi mdi-check-all"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </a>
            @empty
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <p class="notify-details">
                        Aucune notification
                    </p>
                </a>
            @endforelse
        </div>
    </div>
</li>

<script>
function resetNotificationCount() {
    // Hide the notification badge
    const badge = document.getElementById('notification-badge');
    if (badge) {
        badge.style.display = 'none';
    }
    
    // Mark all as read in the backend
    fetch('{{ route('notifications.markAllAsRead') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    }).catch(error => console.error('Error:', error));
}
</script>