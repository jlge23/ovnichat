import { useState, useCallback } from "react";
import LayoutAuth from "@/Layouts/LayoutAuth";
import { Head, router, usePage } from "@inertiajs/react";
import {
    ReactFlow,
    applyNodeChanges,
    applyEdgeChanges,
    addEdge,
    Node,
    Edge,
    NodeChange,
    EdgeChange,
    Connection,
    MiniMap,
    Background,
    BackgroundVariant,
    Handle,
    Position,
    NodeProps,
} from "@xyflow/react";
import "@xyflow/react/dist/style.css";
import FAB from "@/components/FAB";
import { showToast } from "@/utils/Toast";
import { route } from "ziggy-js";

// Definición de tipos
type MyNodeData = { label: string; type?: string };

// Nodos y conexiones iniciales
const initialNodes: Node<MyNodeData>[] = [
    {
        id: `${+new Date()}`,
        position: { x: 0, y: 0 },
        data: { label: "Inicio", type: "start" },
        type: "custom",
    },
];

const initialEdges: Edge[] = [
    // { id: "n1-n2", source: "n1", target: "n2", type: "default" },
];

// Definición del nodo
type CustomNode = Node<MyNodeData, "custom">;

// Props del componente
type CustomNodeProps = NodeProps<CustomNode>;

function CustomNode({ data }: CustomNodeProps) {
    const baseClasses = "p-4 rounded shadow cursor-pointer select-none";

    const typeClasses =
        data.type === "start"
            ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border-2 border-green-400"
            : data.type === "tipo-1"
            ? "bg-blue-300 text-black dark:bg-blue-800 dark:text-white border-2 border-blue-400"
            : data.type === "tipo-2"
            ? "bg-purple-300 text-black dark:bg-purple-600 dark:text-white border-2 border-purple-400"
            : data.type === "end"
            ? "bg-red-300 text-black dark:bg-red-800 dark:text-white border-2 border-red-300"
            : "bg-white text-black dark:bg-gray-800 dark:text-white border-2 border-gray-300";

    return (
        <div className={`${baseClasses} ${typeClasses}`}>
            {data.type !== "start" ? (
                <Handle type="target" position={Position.Top} />
            ) : null}
            {data.label}
            {data.type !== "end" ? (
                <Handle type="source" position={Position.Bottom} />
            ) : null}
        </div>
    );
}

const nodeTypes = {
    custom: CustomNode,
};

export default function Flow() {
    const {
        props: { appName },
    } = usePage();

    const [nodes, setNodes] = useState<Node<MyNodeData>[]>(initialNodes);
    const [edges, setEdges] = useState<Edge[]>(initialEdges);

    const onNodesChange = useCallback(
        (changes: NodeChange[]) => {
            const updatedNodes = applyNodeChanges(
                changes,
                nodes
            ) as Node<MyNodeData>[];
            setNodes(updatedNodes);
        },
        [nodes]
    );

    const onEdgesChange = useCallback(
        (changes: EdgeChange[]) =>
            setEdges((es) => applyEdgeChanges(changes, es)),
        []
    );

    const onConnect = useCallback(
        (connection: Connection) => setEdges((es) => addEdge(connection, es)),
        []
    );

    const onDragOver = useCallback((event: React.DragEvent) => {
        event.preventDefault();
        event.dataTransfer.dropEffect = "move";
    }, []);

    const onDrop = useCallback((event: React.DragEvent) => {
        event.preventDefault();

        const nodeType = event.dataTransfer.getData("application/reactflow");
        if (!nodeType) return;

        // Verificar si ya existe un nodo de tipo 'end'
        const hasEndNode = nodes.some((node) => node.data.type === "end");
        if (nodeType === "end" && hasEndNode) {
            return; // No permitir agregar más nodos de tipo 'end'
        }

        const position = {
            x: 0,
            y: 100,
        };

        const newNode: Node<MyNodeData> = {
            id: `${+new Date()}`,
            type: "custom", // siempre usamos el renderer custom
            position,
            data: { label: `${nodeType} nodo`, type: nodeType }, // aquí guardas tu tipo lógico
        };

        setNodes((nds) => nds.concat(newNode));
    }, []);

    function handleSave() {
        if (edges.length <= 0) return;

        const data = {
            nodes: nodes.map(({ id, position, data }) => ({
                id,
                position,
                data,
            })),
            edges: edges.map(({ id, source, target, type }) => ({
                id,
                source,
                target,
                type,
            })),
        };

        router.post(route("flujos.store"), data, {
            onSuccess: () =>
                showToast(
                    "success",
                    "Cambios al diagrama de flujo guardados correctamente."
                ),
            onError: (err) => {
                showToast(
                    "error",
                    "Error al guardar los datos del diagrama de flujo."
                );
                console.error(err);
            },
        });

        console.log("nodes: ", nodes);
        console.log("edges: ", edges);
    }

    return (
        <LayoutAuth>
            <Head>
                <title>
                    {appName
                        ? `${appName} - Constructor de Flujos`
                        : "Constructor de Flujos"}
                </title>
            </Head>

            <div className="flex w-screen h-screen py-5">
                {/* Sidebar izquierda */}
                <div className="w-48 bg-gray-200 dark:bg-gray-500 rounded-l-lg p-4">
                    <div
                        draggable
                        onDragStart={(e) =>
                            e.dataTransfer.setData(
                                "application/reactflow",
                                "tipo-1"
                            )
                        }
                        className="bg-gray-700 p-2 mb-2 cursor-grab rounded flex gap-3 items-center"
                    >
                        <div className="bg-blue-600 size-8 flex justify-center items-center rounded">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                height="24px"
                                viewBox="0 -960 960 960"
                                width="24px"
                                fill="#e3e3e3"
                            >
                                <path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                        </div>
                        Mensaje
                    </div>
                    <div
                        draggable
                        onDragStart={(e) =>
                            e.dataTransfer.setData(
                                "application/reactflow",
                                "tipo-2"
                            )
                        }
                        className="bg-gray-700 p-2 mb-2 cursor-grab rounded flex gap-3 items-center"
                    >
                        <div className="bg-purple-600 size-8 flex justify-center items-center rounded">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                height="24px"
                                viewBox="0 -960 960 960"
                                width="24px"
                                fill="#e3e3e3"
                            >
                                <path d="M280-280h280v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm-80 480q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z" />
                            </svg>
                        </div>
                        Plantilla
                    </div>

                    <div
                        draggable
                        onDragStart={(e) =>
                            e.dataTransfer.setData(
                                "application/reactflow",
                                "end"
                            )
                        }
                        className="bg-gray-700 p-2 mb-2 cursor-grab rounded flex gap-3 items-center"
                    >
                        <div className="bg-red-700 size-8 flex justify-center items-center rounded">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                height="24px"
                                viewBox="0 -960 960 960"
                                width="24px"
                                fill="#e3e3e3"
                            >
                                <path d="M441-120v-86q-53-12-91.5-46T293-348l74-30q15 48 44.5 73t77.5 25q41 0 69.5-18.5T587-356q0-35-22-55.5T463-458q-86-27-118-64.5T313-614q0-65 42-101t86-41v-84h80v84q50 8 82.5 36.5T651-650l-74 32q-12-32-34-48t-60-16q-44 0-67 19.5T393-614q0 33 30 52t104 40q69 20 104.5 63.5T667-358q0 71-42 108t-104 46v84h-80Z" />
                            </svg>
                        </div>
                        Cierre
                    </div>
                </div>

                {/* Área de diagrama */}
                <div className="flex-1 bg-gray-800 rounded-r-lg">
                    <ReactFlow
                        nodes={nodes}
                        edges={edges}
                        onNodesChange={onNodesChange}
                        onEdgesChange={onEdgesChange}
                        onConnect={onConnect}
                        onDrop={onDrop}
                        onDragOver={onDragOver}
                        fitView
                        deleteKeyCode="Delete"
                        nodeTypes={nodeTypes}
                    >
                        <MiniMap />
                        <Background
                            variant={BackgroundVariant.Dots}
                            gap={12}
                            size={1}
                        />
                    </ReactFlow>
                </div>
            </div>
            <FAB
                onClick={handleSave}
                icon="save"
                disabled={edges.length <= 0}
            />
        </LayoutAuth>
    );
}
