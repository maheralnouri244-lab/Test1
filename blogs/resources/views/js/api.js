// public/js/api.js
const API_BASE = '/api';

export async function apiFetch(path, opts = {}) {
  const token = localStorage.getItem('token');
  const headers = Object.assign({
    'Accept': 'application/json',
  }, opts.headers || {});

  // إذا هناك جسم JSON، اضبط Content-Type تلقائياً
  if (opts.body && typeof opts.body === 'object') {
    headers['Content-Type'] = 'application/json';
    opts.body = JSON.stringify(opts.body);
  }

  if (token) headers['Authorization'] = 'Bearer ' + token;

  const res = await fetch(API_BASE + path, Object.assign({}, opts, { headers }));

  const text = await res.text();
  const data = text ? JSON.parse(text) : null;

  if (!res.ok) {
    // إذا 401 → نفّذ خروج محلي
    if (res.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      // لا توجه تلقائياً هنا، دع الصفحة تتصرف
    }
    const err = { status: res.status, data };
    throw err;
  }
  return data;
}