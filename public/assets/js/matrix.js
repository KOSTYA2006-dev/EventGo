// Анимация матрицы в стиле фильма
(function() {
    const matrixChars = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲンABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const matrixContainer = document.getElementById('matrix-background');
    
    if (!matrixContainer) return;
    
    const columnWidth = 25;
    const columns = Math.floor(window.innerWidth / columnWidth);
    const chars = matrixChars.split('');
    
    function createColumn(index) {
        const column = document.createElement('div');
        column.className = 'matrix-column';
        column.style.left = (index * columnWidth) + 'px';
        const duration = Math.random() * 4 + 3; // 3-7 секунд
        column.style.animationDuration = duration + 's';
        column.style.animationDelay = Math.random() * 2 + 's';
        
        let text = '';
        const length = Math.floor(Math.random() * 40) + 25;
        for (let i = 0; i < length; i++) {
            const char = chars[Math.floor(Math.random() * chars.length)];
            // Первые символы ярче
            const opacity = i < 3 ? 1 : Math.max(0.3, 1 - (i / length));
            text += `<span style="opacity: ${opacity}">${char}</span><br>`;
        }
        column.innerHTML = text;
        
        matrixContainer.appendChild(column);
        
        // Пересоздаем колонку после анимации
        setTimeout(() => {
            if (column.parentNode) {
                column.remove();
                createColumn(index);
            }
        }, (duration + 2) * 1000);
    }
    
    // Создаем колонки
    for (let i = 0; i < columns; i++) {
        createColumn(i);
    }
    
    // Обновляем при изменении размера окна
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            matrixContainer.innerHTML = '';
            const newColumns = Math.floor(window.innerWidth / columnWidth);
            for (let i = 0; i < newColumns; i++) {
                createColumn(i);
            }
        }, 250);
    });
})();

