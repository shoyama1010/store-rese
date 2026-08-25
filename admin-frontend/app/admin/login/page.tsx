"use client";

import { useState } from "react";
import { api } from "@/lib/api";
import { initCsrf } from "@/lib/auth";
import { useRouter } from "next/navigation";

export default function AdminLoginPage() {
  const router = useRouter();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      // ① CSRF cookie を取得
      await api("/sanctum/csrf-cookie", {
        method: "GET",
      });

      // ② ログインAPIを叩く
      await api("/multi/login", {
        method: "POST",
        body: JSON.stringify({
          email,
          password,
          guard: "admin",
        }),
      });

      // ③ 成功したら遷移
      router.push("/admin/dashboard");
    } catch (err: unknown) {
      if (err instanceof Error) {
        setError("ログインに失敗しました: " + err.message);
      } else {
        setError("ログインに失敗しました: 不明なエラー");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <main style={{ maxWidth: 400, margin: "2rem auto" }}>
      <h1>管理者ログイン</h1>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Email</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button type="submit" disabled={loading}>
          {loading ? "ログイン中..." : "ログイン"}
        </button>
      </form>

      {error && <p style={{ color: "red" }}>{error}</p>}
    </main>
  );
}
