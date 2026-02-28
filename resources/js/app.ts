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

            // 二重送信防止
            target.disabled = true;

            try {
                // CSRFトークンの取得
                const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

                // サーバーへPATCHリクエストを送信
                const response = await fetch(`/tasks/${taskId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    console.log(`Task ${taskId} updated via Ajax!`);
                    
                    location.reload(); 
                } else {
                    throw new Error('サーバーエラーが発生しました');
                }
            } catch (error) {
                console.error('Ajax Error:', error);
                alert('通信に失敗しました。ページを再読み込みしてやり直してください。');
                target.checked = !target.checked; // 失敗したので元のチェック状態に戻す
            } finally {
                target.disabled = false;
            }
        });
    });
};

// ページの読み込みが終わったらすべての機能を起動
document.addEventListener('DOMContentLoaded', () => {
    initValidation();
    initAjaxToggle();
});