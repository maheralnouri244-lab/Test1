// public/js/auth.js
import { apiFetch } from './api.js';

export async function login(credentials) {
  const data = await apiFetch('/login', { method: 'POST', body: credentials });
  localStorage.setItem('token', data.access_token);
  localStorage.setItem('user', JSON.stringify(data.user));
  return data.user;
}

export async function register(credentials) {
  const data = await apiFetch('/register', { method: 'POST', body: credentials });
  localStorage.setItem('token', data.access_token);
  localStorage.setItem('user', JSON.stringify(data.user));
  return data.user;
}

export function logout() {
  const token = localStorage.getItem('token');
  if (token) {
    // fire-and-forget logout to server
    fetch('/api/logout', { method: 'POST', headers: { 'Authorization': 'Bearer ' + token }});
  }
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  location.href = 'login.html';
}

export function currentUser() {
  const raw = localStorage.getItem('user');
  return raw ? JSON.parse(raw) : null;
}

export function isAuthenticated() {
  return !!localStorage.getItem('token');
}