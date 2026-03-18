/**
 * フォームのリアルタイム・バリデーション設定
 */
const initValidation = (): void => {
    const titleInput = document.getElementById('task-title-input') as HTMLInputElement | null;
    const errorDisplay = document.getElementById('title-error-msg') as HTMLParagraphElement | null;
    const submitButton = document.getElementById('submit-button') as HTMLButtonElement | null;

    if (!titleInput || !errorDisplay || !submitButton) return;

    const MAX_LENGTH = 20;

    titleInput.addEventListener('input', () => {
        const currentLength = titleInput.value.length;

        if (currentLength > MAX_LENGTH) {
            errorDisplay.textContent = `⚠️ ${MAX_LENGTH}文字以内で入力してください（現在: ${currentLength}文字）`;
            errorDisplay.classList.remove('hidden');
            titleInput.classList.add('border-red-500', 'ring-red-500');
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            errorDisplay.classList.add('hidden');
            titleInput.classList.remove('border-red-500', 'ring-red-500');
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
};

/**
 * 非同期でのタスク完了状態切り替え
 */
const initAjaxToggle = (): void => {
    const taskCheckboxes = document.querySelectorAll<HTMLInputElement>('.task-toggle-checkbox');

    taskCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', async (event: Event) => {
            const target = event.target as HTMLInputElement;
            const taskId = target.dataset.id;
            
            if (!taskId) return;

            target.disabled = true;

            try {
                const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

                const response = await fetch(`/tasks/${taskId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    location.reload(); 
                } else {
                    throw new Error('サーバーエラーが発生しました');
                }
            } catch (error) {
                console.error('Ajax Error:', error);
                alert('通信に失敗しました。');
                target.checked = !target.checked;
            } finally {
                target.disabled = false;
            }
        });
    });
};

/**
 * 【追加】フロントエンドでのリアルタイム検索
 */
const initSearch = (): void => {
    // 検索窓を取得 (index.blade.php の name="search" を指定)
    const searchInput = document.querySelector('input[name="search"]') as HTMLInputElement | null;
    // 全てのタスクアイテム (li.group) を取得
    const taskItems = document.querySelectorAll<HTMLLIElement>('li.group');

    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        const keyword = searchInput.value.toLowerCase();

        taskItems.forEach(item => {
            // タスク名のテキストが含まれる要素 (tracking-wideクラス) を取得
            const titleElement = item.querySelector('.tracking-wide');
            const titleText = titleElement?.textContent?.toLowerCase() || '';
            
            // キーワードが含まれているか判定して表示/非表示を切り替え
            if (titleText.includes(keyword)) {
                item.style.setProperty('display', 'flex', 'important'); 
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    });
};

// すべての機能を起動
document.addEventListener('DOMContentLoaded', () => {
    initValidation();
    initAjaxToggle();
    initSearch(); // これを忘れずに呼ぶ
});