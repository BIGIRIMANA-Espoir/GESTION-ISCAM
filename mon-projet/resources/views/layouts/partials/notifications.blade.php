@php
    $user = Auth::user();
    
    // MÉTHODE ULTRA-SÉCURISÉE
    if (method_exists($user, 'unreadNotifications')) {
        $unreadCount = $user->unreadNotifications->count();
    } else {
        // Fallback si la méthode n'existe pas
        $unreadCount = 0;
    }
    
    $recentNotifications = $user->notifications()->latest()->take(5)->get();
@endphp

<li class="nav-item dropdown" style="list-style: none; margin-right: 10px;">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button"
       data-bs-toggle="dropdown" aria-expanded="false" style="color: #666; padding: 8px;">
        <i class="fas fa-bell fa-lg"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                {{ $unreadCount }}
            </span>
        @endif
    </a>
    
    <ul class="dropdown-menu dropdown-menu-end shadow notification-menu" aria-labelledby="notificationDropdown">
        
        <li class="dropdown-header bg-light py-2 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold">Notifications</span>
                @if($unreadCount > 0)
                    <a href="{{ route('notifications.mark-all-read') }}" class="text-decoration-none small">
                        Tout marquer comme lu
                    </a>
                @endif
            </div>
        </li>
        
        <li>
            @forelse($recentNotifications as $notification)
                <div class="dropdown-item p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}"
                     style="white-space: normal;">
                    <div class="d-flex justify-content-between">
                        <div class="notification-content">
                            @if(isset($notification->data['message']))
                                <p class="mb-1 small">{{ $notification->data['message'] }}</p>
                            @else
                                <p class="mb-1 small">Nouvelle notification</p>
                            @endif
                            
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        
                        @if(!$notification->read_at)
                            <a href="{{ route('notifications.mark-read', $notification->id) }}" 
                               class="ms-2 text-primary" title="Marquer comme lu">
                                <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p class="mb-0 small">Aucune notification</p>
                </div>
            @endforelse
        </li>
        
        @if($recentNotifications->count() > 0)
            <li class="dropdown-footer p-2 text-center border-top">
                <a href="{{ route('notifications.index') }}" class="text-decoration-none small">
                    Voir toutes les notifications
                </a>
            </li>
        @endif
    </ul>
</li>