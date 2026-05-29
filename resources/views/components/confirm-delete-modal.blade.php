@props(['id', 'title' => 'Konfirmasi Hapus', 'message', 'action'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:3rem;"></i>
                <h5 class="mt-3">{{ $title }}</h5>
                <p class="text-muted">{{ $message }} Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ $action }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
