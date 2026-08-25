"use client";

import { useState } from "react";
import { api } from "@/lib/api";
import { initCsrf } from "@/lib/auth";

export default function TestApi() {
  const [out, setOut] = useState<string>("");

  const callPing = async () => {
    try {
      const data = await api("/api/ping");
      setOut("PING OK:\n" + JSON.stringify(data, null, 2));
    } catch (e: unknown) {
      if (e instanceof Error) {
        setOut("PING ERR: " + e.message);
      } else {
        setOut("PING ERR: 不明なエラー");
      }
    }
  };

  const loginAdmin = async () => {
    try {
      // 1) CSRF Cookie
      await fetch(`${process.env.NEXT_PUBLIC_API_URL}/sanctum/csrf-cookie`, {
        credentials: "include",
      });
      // 2) ログイン
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/login`, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email: "admin@example.com",
          password: "admin1234",
        }),
      });
      if (!res.ok) throw new Error("login failed");
      setOut("LOGIN OK");
    } catch (e: unknown) {
      if (e instanceof Error) {
        setOut("LOGIN ERR: " + e.message);
      } else {
        setOut("LOGIN ERR: 不明なエラー");
      }
    }
  };

  const callMe = async () => {
    try {
      const data = await api("/api/admin/me");
      setOut("ME OK:\n" + JSON.stringify(data, null, 2));
    } catch (e: unknown) {
      if (e instanceof Error) {
        setOut("ME ERR: " + e.message);
      } else {
        setOut("ME ERR: 不明なエラー");
      }
    }
  };

  const logoutAdmin = async () => {
    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/admin/logout`, {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
      });
      if (!res.ok) throw new Error("logout failed");
      setOut("LOGOUT OK");
    } catch (e: unknown) {
      if (e instanceof Error) {
        setOut("LOGOUT ERR: " + e.message);
      } else {
        setOut("LOGOUT ERR: 不明なエラー");
      }
    }
  };

  return (
    <main style={{ padding: 24 }}>
      <h1>Laravel API Test</h1>
      <div style={{ display: "flex", gap: 12, marginBottom: 12 }}>
        <button onClick={callPing}>/api/ping</button>
        <button onClick={loginAdmin}>admin login</button>
        <button onClick={callMe}>/api/admin/me</button>
        <button onClick={logoutAdmin}>admin logout</button>
      </div>
      <pre style={{ background: "#111", color: "#0f0", padding: 12, whiteSpace: "pre-wrap" }}>
        {out}
      </pre>
    </main>
  );
}
