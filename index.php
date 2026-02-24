<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3C與溫習管理 - 精確比例平衡版</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .glass-card {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <header class="text-center mb-8">
            <h1 class="text-3xl font-black text-gray-800 mb-2">⚖️ 精確比例平衡助手</h1>
            <p class="text-gray-600">確保今日結束時，學習與娛樂比例完全達標</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. 3C 使用時段加總 -->
                <div class="glass-card rounded-3xl shadow-xl p-6 border-2 border-orange-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-orange-700 flex items-center">
                            <span class="mr-2">📱</span> 3C 零碎時間加總
                        </h2>
                        <button onclick="addTimeRow()" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-xl transition shadow-md">
                            + 新增時段
                        </button>
                    </div>

                    <div id="timeRows" class="space-y-3">
                        <div class="time-row flex flex-wrap items-center gap-2 p-3 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-gray-400">從</span>
                                <input type="time" class="start-time p-2 rounded-lg border font-mono">
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-gray-400">至</span>
                                <input type="time" class="end-time p-2 rounded-lg border font-mono" onchange="calculateIntervals()">
                            </div>
                            <div class="flex-1 text-right">
                                <span class="row-duration text-sm font-bold text-orange-500 mr-2">0m</span>
                                <button onclick="removeTimeRow(this)" class="text-gray-300 hover:text-red-500 text-xl px-2">×</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-orange-50 rounded-2xl flex justify-between items-center">
                        <div class="text-gray-600 text-sm font-bold">加總：<span id="totalPeriodDisplay" class="text-orange-700 text-lg ml-1">0 小時 0 分鐘</span></div>
                        <button onclick="applyToUsed3C()" class="bg-white border border-orange-300 text-orange-600 hover:bg-orange-100 px-4 py-2 rounded-xl text-sm font-bold transition">填入今日已用</button>
                    </div>
                </div>

                <!-- 2. 計算參數 -->
                <div class="glass-card rounded-3xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center">⚙️ 目標與進度</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-500 uppercase tracking-wide">每日 3C 總額度 (上限)</label>
                            <div class="flex items-center space-x-2"><input type="number" id="limitH" value="4" class="w-full p-2 border rounded-xl"><span>h</span><input type="number" id="limitM" value="0" class="w-full p-2 border rounded-xl"><span>m</span></div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-500 uppercase tracking-wide">每日溫習總目標</label>
                            <div class="flex items-center space-x-2"><input type="number" id="studyH" value="2" class="w-full p-2 border rounded-xl"><span>h</span><input type="number" id="studyM" value="0" class="w-full p-2 border rounded-xl"><span>m</span></div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-orange-600">今日已用 3C</label>
                            <div class="flex items-center space-x-2"><input type="number" id="used3cH" value="2" class="w-full p-2 border rounded-xl bg-orange-50"><span>h</span><input type="number" id="used3cM" value="0" class="w-full p-2 border rounded-xl bg-orange-50"><span>m</span></div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-blue-600">今日已溫習</label>
                            <div class="flex items-center space-x-2"><input type="number" id="doneStudyH" value="0" class="w-full p-2 border rounded-xl bg-blue-50"><span>h</span><input type="number" id="doneStudyM" value="0" class="w-full p-2 border rounded-xl bg-blue-50"><span>m</span></div>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-bold text-purple-600">距離睡覺還有多久 (剩餘可用總時間)</label>
                            <div class="flex items-center space-x-2"><input type="number" id="sleepH" value="2" class="w-full p-2 border rounded-xl bg-purple-50"><span>h</span><input type="number" id="sleepM" value="0" class="w-full p-2 border rounded-xl bg-purple-50"><span>m</span></div>
                        </div>
                    </div>
                    <button onclick="mainCalculate()" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl transition">開始精確比例計算</button>
                </div>
            </div>

            <!-- Right Side: Results -->
            <div id="resultArea" class="hidden space-y-4">
                <div class="bg-gray-900 text-white rounded-3xl p-6 shadow-2xl">
                    <h3 class="text-xs font-bold opacity-60 mb-6 tracking-widest uppercase text-center">平衡分配結果</h3>
                    <div class="space-y-8">
                        <div class="text-center">
                            <p class="text-blue-400 text-xs font-bold mb-1">📚 剩餘應溫習時間</p>
                            <p id="resStudy" class="text-4xl font-black text-white">--</p>
                        </div>
                        <div class="text-center">
                            <p class="text-orange-400 text-xs font-bold mb-1">📱 剩餘可用 3C</p>
                            <p id="res3c" class="text-4xl font-black text-white">--</p>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-5 rounded-2xl text-[13px] text-gray-600 border-l-4 border-indigo-500 shadow-sm leading-relaxed">
                    <div id="logicExplain"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let globalSumMins = 0;

        function addTimeRow() {
            const container = document.getElementById('timeRows');
            const div = document.createElement('div');
            div.className = "time-row flex flex-wrap items-center gap-2 p-3 bg-white rounded-2xl border border-gray-100 shadow-sm";
            div.innerHTML = `
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-gray-400">從</span>
                    <input type="time" class="start-time p-2 rounded-lg border font-mono">
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-gray-400">至</span>
                    <input type="time" class="end-time p-2 rounded-lg border font-mono" onchange="calculateIntervals()">
                </div>
                <div class="flex-1 text-right">
                    <span class="row-duration text-sm font-bold text-orange-500 mr-2">0m</span>
                    <button onclick="removeTimeRow(this)" class="text-gray-300 hover:text-red-500 text-xl px-2">×</button>
                </div>`;
            container.appendChild(div);
        }

        function removeTimeRow(btn) { btn.closest('.time-row').remove(); calculateIntervals(); }

        function calculateIntervals() {
            const rows = document.querySelectorAll('.time-row');
            let total = 0;
            rows.forEach(row => {
                const start = row.querySelector('.start-time').value;
                const end = row.querySelector('.end-time').value;
                if (start && end) {
                    const [sH, sM] = start.split(':').map(Number);
                    const [eH, eM] = end.split(':').map(Number);
                    let diff = (eH * 60 + eM) - (sH * 60 + sM);
                    if (diff < 0) diff += 1440;
                    row.querySelector('.row-duration').textContent = formatToHM(diff);
                    total += diff;
                }
            });
            globalSumMins = total;
            document.getElementById('totalPeriodDisplay').textContent = `${Math.floor(total/60)} 小時 ${total%60} 分鐘`;
        }

        function applyToUsed3C() {
            document.getElementById('used3cH').value = Math.floor(globalSumMins / 60);
            document.getElementById('used3cM').value = globalSumMins % 60;
        }

        function formatToHM(mins) {
            mins = Math.round(mins);
            const h = Math.floor(mins / 60);
            const m = mins % 60;
            return h > 0 ? `${h}h ${m}m` : `${m}m`;
        }

        function mainCalculate() {
            // 讀取輸入
            const limit3c = (Number(document.getElementById('limitH').value) || 0) * 60 + (Number(document.getElementById('limitM').value) || 0);
            const targetStudy = (Number(document.getElementById('studyH').value) || 0) * 60 + (Number(document.getElementById('studyM').value) || 0);
            const used3c = (Number(document.getElementById('used3cH').value) || 0) * 60 + (Number(document.getElementById('used3cM').value) || 0);
            const doneStudy = (Number(document.getElementById('doneStudyH').value) || 0) * 60 + (Number(document.getElementById('doneStudyM').value) || 0);
            const remTime = (Number(document.getElementById('sleepH').value) || 0) * 60 + (Number(document.getElementById('sleepM').value) || 0);

            if (remTime <= 0 || limit3c <= 0 || targetStudy <= 0) {
                alert("請輸入有效的時間數值。"); return;
            }

            // 1. 計算目標比例 R (讀書/3C)
            const R = targetStudy / limit3c;

            // 2. 使用代數方程解出新增讀書時間 S_add
            // (doneStudy + S_add) / (used3c + (remTime - S_add)) = R
            // S_add + doneStudy = R * used3c + R * remTime - R * S_add
            // S_add * (1 + R) = R * (used3c + remTime) - doneStudy
            let S_add = (R * (used3c + remTime) - doneStudy) / (1 + R);
            
            // 3. 邊界條件處理
            if (S_add > remTime) {
                // 如果算出來讀書時間比剩餘時間還多，代表即便全讀書也補不完比例
                S_add = remTime;
            } else if (S_add < 0) {
                // 如果算出來是負數，代表目前的讀書量已經超過比例所需，剩下的時間可以全部給 3C
                S_add = 0;
            }

            let C_add = remTime - S_add;

            // 4. 每日總額度限制 (3C 不得超過上限)
            const maxC_possible = Math.max(0, limit3c - used3c);
            if (C_add > maxC_possible) {
                C_add = maxC_possible;
                S_add = remTime - C_add; // 剩下的全給讀書
            }

            // 更新結果
            document.getElementById('resStudy').textContent = formatToHM(S_add);
            document.getElementById('res3c').textContent = formatToHM(C_add);
            document.getElementById('resultArea').classList.remove('hidden');

            // 顯示邏輯說明
            const ratioText = `1 : ${(1/R).toFixed(1)}`;
            document.getElementById('logicExplain').innerHTML = `
                <b>精確平衡邏輯：</b><br>
                1. 您的目標比例是 <b>${ratioText}</b> (讀書:3C)。<br>
                2. 您目前已用 3C ${formatToHM(used3c)}，依比例「欠債」讀書量為 ${formatToHM(used3c * R)}。<br>
                3. 在剩餘的 ${formatToHM(remTime)} 中，系統先分配時間「還清債務」，再按比例分配餘下時間。<br>
                4. 最終今日總計：讀書 ${formatToHM(doneStudy + S_add)} / 3C ${formatToHM(used3c + C_add)}，完全符合比例。
            `;
            document.getElementById('resultArea').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>