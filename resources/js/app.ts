// すべてのタスクチェックボックスを取得
const taskCheckboxes = document.querySelectorAll<HTMLInputElement>('.task-toggle-checkbox');

taskCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', (event: Event) => {
        const target = event.target as HTMLInputElement;
        const form = target.closest('form');
        
        if (form) {
            form.submit();
        }
    });
});