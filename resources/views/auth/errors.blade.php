<!-- Global Alert Container - Fixed Position Overlay -->
<div id="globalAlertContainer">
    <div style="pointer-events: auto;">
        @if(session('status'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{session('status')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{session('error')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(isset($errors) && $errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endforeach
        @endif
    </div>
</div>

<style>
    /* Global alert container - fixed overlay */
    #globalAlertContainer {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1060;
        width: auto;
        max-width: 600px;
        pointer-events: none;
    }
    
    #globalAlertContainer .alert {
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        pointer-events: auto;
    }
    
    /* Fix close button positioning for Bootstrap 5 */
    #globalAlertContainer .alert-dismissible {
        padding-right: 3rem;
    }
    
    #globalAlertContainer .alert-dismissible .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        padding: 0.75rem 1rem;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        #globalAlertContainer {
            width: 95%;
        }
    }
</style>

