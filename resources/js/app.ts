//共有相手の入力行を動的に追加・削除する
const initShareHandler = (): void => {
    const container = document.getElementById('share-emails-container') as HTMLDivElement | null;
    const addBtn = document.getElementById('add-email-btn') as HTMLButtonElement | null;

    if (!container || !addBtn) return;

    addBtn.addEventListener('click', () => {
        // 1. 外側のラッパー作成
        const wrapper = document.createElement('div');
        wrapper.className = 'flex gap-2 animate-fade-in items-center';

        // 2. 入力欄の作成
        const input = document.createElement('input');
        input.type = 'email';
        input.name = 'share_emails[]';
        input.placeholder = 'メールアドレス';
        input.className = 'flex-1 border-gray-100 rounded-xl text-sm focus:border-blue-400 focus:ring-0 bg-gray-50/50 transition-all font-medium placeholder-gray-300';

        // 3. 削除ボタンの作成
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'p-2 text-gray-300 hover:text-red-500 transition-colors group';
        removeBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        `;

        // 4. 削除イベントの紐付け
        removeBtn.addEventListener('click', () => {
            wrapper.remove();
        });

        // 5. DOMへの追加
        wrapper.appendChild(input);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
    });
};

// フォームのリアルタイム・バリデーション設定
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

// 非同期でのタスク完了状態切り替え
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

// フロントエンドでのリアルタイム検索
const initSearch = (): void => {
    const searchInput = document.querySelector('input[name="search"]') as HTMLInputElement | null;
    const taskItems = document.querySelectorAll<HTMLLIElement>('li.group');

    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        const keyword = searchInput.value.toLowerCase();

        taskItems.forEach(item => {
            const titleElement = item.querySelector('.truncate');
            const titleText = titleElement?.textContent?.toLowerCase() || '';
            
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
    initSearch();
    initShareHandler();
});