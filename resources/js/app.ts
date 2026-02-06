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
 * 非同期（Ajax）でのタスク完了状態切り替え
 */
const initAjaxToggle = (): void => {
    // クラス名でチェックボックスをすべて取得
    const taskCheckboxes = document.querySelectorAll<HTMLInputElement>('.task-toggle-checkbox');

    taskCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', async (event: Event) => {
            const target = event.target as HTMLInputElement;
            const taskId = target.dataset.id; // data-id 属性から取得
            
            if (!taskId) return;

            // 通信中の多重クリック防止
            target.disabled = true;

            try {
                // CSRFトークンの取得
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
                    // 通信成功：画面全体のリロードなしで処理終了
                    // 本来はここでDOM操作（場所の移動など）を行うとさらによい
                    console.log(`Task ${taskId} toggled!`);
                    // 状態変更を反映させるため、今回は簡易的にリロードさせるか、
                    // もしくは完全に非同期にするなら以下の location.reload() を消してDOM操作を書きます。
                    location.reload(); 
                } else {
                    throw new Error('Update failed');
                }
            } catch (error) {
                console.error('Ajax Error:', error);
                alert('通信に失敗しました。');
                target.checked = !target.checked; // 失敗したので元の状態に戻す
            } finally {
                target.disabled = false;
            }
        });
    });
};

// DOM構築完了後に実行
document.addEventListener('DOMContentLoaded', () => {
    initValidation();
    initAjaxToggle();
});