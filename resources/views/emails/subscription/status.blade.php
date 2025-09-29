@component('mail::message')
    # {{ $status === 'approved' ? '✅ Subscription Request Approved' : '❌ Subscription Request Rejected' }}

    @if($status === 'approved')
        Your subscription request has been approved. 🎉
        You can now start using your generator services.
    @else
        Your subscription request has been rejected.
         @if($admin_notes)
            **Reason:** {{ $admin_notes }}
        @endif
    @endif

    Thanks,
    {{ 'PowerFlow Team' }}
@endcomponent
