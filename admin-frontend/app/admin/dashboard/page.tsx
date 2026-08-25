"use client";

import { useEffect, useMemo, useState } from "react";
import { api } from "@/lib/api";

type Owner = {
  id: number;
  name: string;
  email: string;
  created_at?: string;
};

export default function AdminDashboardPage() {
  const [owners, setOwners] = useState<Owner[]>([]);
  const [keyword, setKeyword] = useState("");
  const [sort, setSort] = useState<"new" | "old" | "name">("new");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchOwners = async () => {
      try {
        setLoading(true);

        const data = await api("/api/admin/dashboard", {
          method: "GET",
        });

        setOwners(data.owners ?? []);
      } catch (err: unknown) {
        setError("店舗代表者一覧の取得に失敗しました。");
      } finally {
        setLoading(false);
      }
    };

    fetchOwners();
  }, []);

  const filteredOwners = useMemo(() => {
    let result = [...owners];

    if (keyword) {
      result = result.filter((owner) =>
        `${owner.name} ${owner.email}`
          .toLowerCase()
          .includes(keyword.toLowerCase())
      );
    }

    if (sort === "new") {
      result.sort(
        (a, b) =>
          new Date(b.created_at ?? "").getTime() -
          new Date(a.created_at ?? "").getTime()
      );
    }

    if (sort === "old") {
      result.sort(
        (a, b) =>
          new Date(a.created_at ?? "").getTime() -
          new Date(b.created_at ?? "").getTime()
      );
    }

    if (sort === "name") {
      result.sort((a, b) => a.name.localeCompare(b.name, "ja"));
    }

    return result;
  }, [owners, keyword, sort]);

  if (loading) return <p>読み込み中...</p>;

  return (
    <main style={{ maxWidth: "900px", margin: "2rem auto" }}>
      <h1>管理者ダッシュボード</h1>

      {error && <p style={{ color: "red" }}>{error}</p>}

      <div style={{ display: "flex", gap: "1rem", margin: "1.5rem 0" }}>
        <input
          type="text"
          placeholder="名前・メールで検索"
          value={keyword}
          onChange={(e) => setKeyword(e.target.value)}
          style={{
            flex: 1,
            padding: "0.7rem",
            border: "1px solid #ccc",
            borderRadius: "6px",
          }}
        />

        <select
          value={sort}
          onChange={(e) => setSort(e.target.value as "new" | "old" | "name")}
          style={{
            padding: "0.7rem",
            border: "1px solid #ccc",
            borderRadius: "6px",
          }}
        >
          <option value="new">新しい順</option>
          <option value="old">古い順</option>
          <option value="name">名前順</option>
        </select>
      </div>

      <table
        style={{
          width: "100%",
          borderCollapse: "collapse",
          background: "#fff",
        }}
      >
        <thead>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>名前</th>
            <th style={thStyle}>メール</th>
            <th style={thStyle}>登録日</th>
          </tr>
        </thead>
        <tbody>
          {filteredOwners.map((owner) => (
            <tr key={owner.id}>
              <td style={tdStyle}>{owner.id}</td>
              <td style={tdStyle}>{owner.name}</td>
              <td style={tdStyle}>{owner.email}</td>
              <td style={tdStyle}>
                {owner.created_at
                  ? new Date(owner.created_at).toLocaleDateString("ja-JP")
                  : "-"}
              </td>
            </tr>
          ))}

          {filteredOwners.length === 0 && (
            <tr>
              <td style={tdStyle} colSpan={4}>
                該当する店舗代表者はありません。
              </td>
            </tr>
          )}
        </tbody>
      </table>
    </main>
  );
}

const thStyle: React.CSSProperties = {
  border: "1px solid #ddd",
  padding: "0.8rem",
  background: "#f5f5f5",
  textAlign: "left",
};

const tdStyle: React.CSSProperties = {
  border: "1px solid #ddd",
  padding: "0.8rem",
};