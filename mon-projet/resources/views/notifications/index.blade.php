@extends('layouts.dashboard')

@section('title', 'Mes Notifications')
@section('page-title', 'Centre de Notifications')

@section('menu')
    @parent
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell text-primary me-2"></i>
                            Toutes mes notifications
                        </h5>
                        @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-double me-1"></i> Tout marquer comme lu
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex">
                                <div class="me-3">
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary">Nouveau</span>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    @if(isset($notification->data['message']))
                                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                                    @else
                                        <p class="mb-1">Notification</p>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                        
                                        @if(!$notification->read_at)
                                            <a href="{{ route('notifications.mark-read', $notification->id) }}" 
                                               class="btn btn-sm btn-link text-primary p-0">
                                                Marquer comme lu
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                            <h5>Aucune notification</h5>
                            <p class="text-muted">Vous n'avez pas encore de notifications.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection