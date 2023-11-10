import React, { useEffect, useState } from "react";
import { GetItemsSSE } from "../lib/ItemLib";

export interface iAvailableItems {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
}

const ItemSelecterSSE: React.FC = () => {
  const [availableItems, setAvailableItems] = useState<iAvailableItems[]>([]);

  useEffect(() => {
    const fetchAvailableItems = async () => {
      try {
        const itemsData = await GetItemsSSE();
        const items: iAvailableItems[] = Array.isArray(itemsData) ? itemsData : [];
        setAvailableItems(items);
        console.log("ItemSelecter: Data updated");
      } catch (error) {
        console.error('Error fetching available items:', error);
      }
    };

    fetchAvailableItems(); // Initial data fetch

    // // Poll for data updates every 5 seconds (adjust the interval as needed)
    // const intervalId = setInterval(() => {
    //   fetchAvailableItems();
    // }, 5000); // Change the interval to 5000 milliseconds (5 seconds)

    // // Clean up the interval when the component unmounts
    // return () => clearInterval(intervalId);
  }, []);

  return (
    <div className="itemSelecter">
      <h2>Available Items</h2>
      <div style={{ display: "flex", width: "fit-content", justifyContent: "space-around" }}>
        {availableItems.map((item) => (
          <div className="item" key={item.id} style={{ border: "solid 1px black", borderRadius: "20px", padding: "0px 10px", margin: "3px" }}>
            <h3>{item.name}</h3>
          </div>
        ))}
      </div>
    </div>
  );
};
export default ItemSelecterSSE;
