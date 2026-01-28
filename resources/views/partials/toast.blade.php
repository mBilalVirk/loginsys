{{-- ================== TOAST UI (SINGLE FILE) ================== --}}

@if(session('toast'))

<style>
@import url("https://fonts.googleapis.com/css2?family=Varela+Round&display=swap");

:root {
    --tr: all 0.5s ease;
    --ch1:#05478a; --ch2:#0070e0;
    --cs1:#005e38; --cs2:#03a65a;
    --cw1:#c24914; --cw2:#fc8621;
    --ce1:#851d41; --ce2:#db3056;
}

.toast-panel {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    font-family: "Varela Round", sans-serif;
    z-index: 9999;
}

.toast-item {
    animation: show-toast 4s ease 0s 1;
    overflow: hidden;
}

@keyframes show-toast {
    0% { opacity:0; transform:translateY(20px); }
    15% { opacity:1; transform:translateY(0); }
    85% { opacity:1; }
    100% { opacity:0; }
}

.toast {
    --clr: var(--cs1);
    --bg: var(--cs2);
    background: var(--bg);
    color: #fff;
    padding: 1.2rem 1.5rem 1.2rem 4.5rem;
    border-radius: 1.75rem;
    position: relative;
    max-width: 320px;
    box-shadow: 0 10px 25px rgba(0,0,0,.35);
}

.toast h3 {
    margin: 0;
    font-size: 1.1rem;
}

.toast p {
    margin: .25rem 0 0;
    font-size: .9rem;
}

.toast:after {
    content: "";
    position: absolute;
    width: 3rem;
    height: 3rem;
    top: -1.5rem;
    left: 1.25rem;
    background: var(--clr);
    border-radius: 50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:2rem;
    font-weight:bold;
}

.toast.success { --clr:var(--cs1); --bg:var(--cs2); }
.toast.success:after { content:"✔"; }

.toast.error { --clr:var(--ce1); --bg:var(--ce2); }
.toast.error:after { content:"✖"; }

.toast.warning { --clr:var(--cw1); --bg:var(--cw2); }
.toast.warning:after { content:"!"; }

.toast.help { --clr:var(--ch1); --bg:var(--ch2); }
.toast.help:after { content:"?"; }

.close {
    position:absolute;
    right:.75rem;
    top:.75rem;
    cursor:pointer;
    font-size:1.25rem;
}
</style>

<div class="toast-panel">
    <div class="toast-item">
        <div class="toast {{ session('toast.type') }}">
            <span class="close" onclick="this.closest('.toast-item').remove()">×</span>
            <h3>{{ session('toast.title') }}</h3>
            <p>{{ session('toast.message') }}</p>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        document.querySelector('.toast-item')?.remove();
    }, 4000);
</script>

@endif
{{-- ================== END TOAST UI ================== --}}
