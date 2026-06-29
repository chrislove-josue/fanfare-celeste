// ==========================================
// GESTION DU DOCUMENT DANS LE MODAL
// ==========================================

function viewDocumentModal(documentPath, memberName, memberNumber, fileName) {
    // Mettre à jour les informations du modal
    document.getElementById('documentModalMemberName').textContent = memberName;
    document.getElementById('documentModalMemberInfo').textContent = memberName;
    document.getElementById('documentModalMemberNumber').textContent = memberNumber;
    document.getElementById('documentDownloadLink').href = documentPath;
    document.getElementById('documentDownloadLink').download = 'document_' + memberNumber + '.' + fileName.split('.').pop();
    
    // Afficher le loader
    const body = document.getElementById('documentModalBody');
    body.innerHTML = `
        <div class="text-center" id="documentLoading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement du document...</p>
        </div>
    `;
    
    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('documentModal'));
    modal.show();
    
    // Déterminer le type de fichier
    const extension = fileName.split('.').pop().toLowerCase();
    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
    
    if (isImage) {
        // Charger l'image
        const img = new Image();
        img.onload = function() {
            body.innerHTML = `
                <img src="${documentPath}" 
                     alt="Document d'identité" 
                     class="img-fluid rounded shadow-sm"
                     style="max-height: 500px; max-width: 100%; object-fit: contain;">
            `;
        };
        img.onerror = function() {
            body.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Impossible de charger l'image</p>
                    <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> Ouvrir dans un nouvel onglet
                    </a>
                </div>
            `;
        };
        img.src = documentPath;
    } else if (extension === 'pdf') {
        // Afficher le PDF dans un iframe
        body.innerHTML = `
            <iframe src="${documentPath}" 
                    style="width: 100%; height: 500px; border: none; border-radius: 8px;" 
                    frameborder="0"></iframe>
        `;
    } else {
        // Autres types de fichiers
        body.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-primary mb-3"></i>
                <p class="text-muted">Type de fichier non prévisualisable</p>
                <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i> Ouvrir le fichier
                </a>
            </div>
        `;
    }
}