/**
 * Funções JavaScript do Sistema
 * Validações, interatividade e UX
 */

// Confirmação de exclusão
function confirmarExclusao(nome) {
    return confirm(`Tem certeza que deseja excluir "${nome}"?\n\nEsta ação não pode ser desfeita.`);
}

// Validação de formulário de produto
function validarFormularioProduto(form) {
    const nome = form.querySelector('#nome').value.trim();
    const preco = parseFloat(form.querySelector('#preco').value);
    const quantidade = parseInt(form.querySelector('#quantidade_estoque').value);
    const categoria = form.querySelector('#categoria_id').value;
    
    if (nome.length < 3) {
        alert('O nome do produto deve ter pelo menos 3 caracteres.');
        return false;
    }
    
    if (preco <= 0 || isNaN(preco)) {
        alert('O preço deve ser maior que zero.');
        return false;
    }
    
    if (quantidade < 0 || isNaN(quantidade)) {
        alert('A quantidade não pode ser negativa.');
        return false;
    }
    
    if (!categoria) {
        alert('Selecione uma categoria.');
        return false;
    }
    
    return true;
}

// Validação de imagem
function validarImagem(input) {
    const file = input.files[0];
    
    if (!file) return true;
    
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!allowedTypes.includes(file.type)) {
        alert('Formato de arquivo não permitido. Use: JPG, PNG, GIF ou WEBP.');
        input.value = '';
        return false;
    }
    
    if (file.size > maxSize) {
        alert('O arquivo é muito grande. Tamanho máximo: 5MB.');
        input.value = '';
        return false;
    }
    
    // Preview da imagem
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('image-preview');
        if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
    };
    reader.readAsDataURL(file);
    
    return true;
}

// Formata preço em Real
function formatarPreco(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

// Atualiza preview de preço
function atualizarPreviewPreco(input) {
    const valor = parseFloat(input.value);
    const preview = document.getElementById('preco-preview');
    
    if (preview && !isNaN(valor)) {
        preview.textContent = formatarPreco(valor);
    }
}

// Filtro de produtos em tempo real
function filtrarProdutos() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const categoria = document.getElementById('filter-categoria').value;
    const precoMin = parseFloat(document.getElementById('preco-min').value) || 0;
    const precoMax = parseFloat(document.getElementById('preco-max').value) || Infinity;
    
    const produtos = document.querySelectorAll('.product-card');
    
    produtos.forEach(produto => {
        const nome = produto.querySelector('.product-title').textContent.toLowerCase();
        const produtoCategoria = produto.dataset.categoria;
        const preco = parseFloat(produto.dataset.preco);
        
        const matchSearch = nome.includes(searchTerm);
        const matchCategoria = !categoria || produtoCategoria === categoria;
        const matchPreco = preco >= precoMin && preco <= precoMax;
        
        if (matchSearch && matchCategoria && matchPreco) {
            produto.style.display = 'block';
        } else {
            produto.style.display = 'none';
        }
    });
}

// Auto-dismiss de alertas
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000); // Remove após 5 segundos
    });
});

// Validação de formulário de login
function validarFormularioLogin(form) {
    const email = form.querySelector('#email').value.trim();
    const senha = form.querySelector('#senha').value;
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        alert('Por favor, insira um e-mail válido.');
        return false;
    }
    
    if (senha.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        return false;
    }
    
    return true;
}

// Validação de formulário de registro
function validarFormularioRegistro(form) {
    const nome = form.querySelector('#nome').value.trim();
    const email = form.querySelector('#email').value.trim();
    const senha = form.querySelector('#senha').value;
    const confirmarSenha = form.querySelector('#confirmar_senha').value;
    
    if (nome.length < 3) {
        alert('O nome deve ter pelo menos 3 caracteres.');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Por favor, insira um e-mail válido.');
        return false;
    }
    
    if (senha.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        return false;
    }
    
    if (senha !== confirmarSenha) {
        alert('As senhas não coincidem.');
        return false;
    }
    
    return true;
}

// Modal
function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
};

// Contador de caracteres para textarea
function contarCaracteres(textarea, counterId) {
    const counter = document.getElementById(counterId);
    if (counter) {
        const maxLength = textarea.maxLength;
        const currentLength = textarea.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;
    }
}

// Loading button
function adicionarLoading(button) {
    button.disabled = true;
    const originalText = button.textContent;
    button.dataset.originalText = originalText;
    button.innerHTML = '<span class="loading"></span> Processando...';
}

function removerLoading(button) {
    button.disabled = false;
    button.textContent = button.dataset.originalText || 'Enviar';
}

// Máscara de preço
function mascaraPreco(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = (valor / 100).toFixed(2);
    input.value = valor;
}

// Previne duplo submit
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                adicionarLoading(submitButton);
            }
        });
    });
});

// Tooltip simples
function mostrarTooltip(element, message) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = message;
    tooltip.style.position = 'absolute';
    tooltip.style.background = '#333';
    tooltip.style.color = 'white';
    tooltip.style.padding = '5px 10px';
    tooltip.style.borderRadius = '4px';
    tooltip.style.fontSize = '12px';
    tooltip.style.zIndex = '9999';
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
    tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';
    
    setTimeout(() => {
        tooltip.remove();
    }, 2000);
}

// Copiar para clipboard
function copiarParaClipboard(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        alert('Copiado para a área de transferência!');
    }).catch(err => {
        console.error('Erro ao copiar:', err);
    });
}

// Exportação de dados
function exportarTabela(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];
        cols.forEach(col => {
            csvRow.push(col.textContent.trim());
        });
        csv.push(csvRow.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename || 'export.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
