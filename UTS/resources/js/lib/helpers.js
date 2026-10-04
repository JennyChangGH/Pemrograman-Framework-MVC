import { Brain, Cpu, Database, Gamepad2, Globe, Layers, Smartphone } from '@lucide/vue';

export function initials(name = '') {
    return name
        .replace(/^(Dr|Prof|Ir)\.\s*/gi, '')
        .split(/[\s,]+/)
        .filter((w) => w && !w.includes('.'))
        .slice(0, 2)
        .map((w) => w[0].toUpperCase())
        .join('');
}

const gradients = [
    'from-indigo-500 to-violet-500',
    'from-sky-500 to-cyan-400',
    'from-emerald-500 to-teal-400',
    'from-amber-500 to-orange-400',
    'from-rose-500 to-pink-400',
    'from-fuchsia-500 to-purple-500',
];

export function gradientFor(text = '') {
    let hash = 0;
    for (const ch of text) hash = (hash * 31 + ch.charCodeAt(0)) >>> 0;
    return gradients[hash % gradients.length];
}

export const badgeColors = {
    Proposal: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    Berjalan: 'bg-sky-50 text-sky-700 ring-sky-600/20',
    Selesai: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    Wajib: 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
    Pilihan: 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-600/20',
    'Dosen Tetap': 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
    'Dosen Tidak Tetap': 'bg-orange-50 text-orange-700 ring-orange-600/20',
};

export const statusHex = {
    Proposal: '#f59e0b',
    Berjalan: '#0ea5e9',
    Selesai: '#10b981',
};

export const kategoriIcons = {
    'Web Development': Globe,
    'Mobile App': Smartphone,
    'Game Development': Gamepad2,
    'Artificial Intelligence': Brain,
    'Internet of Things': Cpu,
    'Sistem Informasi': Database,
};

export const kategoriIcon = (k) => kategoriIcons[k] ?? Layers;
