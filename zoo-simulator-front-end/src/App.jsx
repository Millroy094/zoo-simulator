import moment from "moment/moment";
import { useState, useEffect, useRef } from "react";
import {
    Container,
    Grid,
    Paper,
    Card,
    Chip,
    IconButton,
    Tooltip,
    Alert,
} from "@mui/material";
import {
    AccessTime,
    LunchDining,
    MoreTime,
    RestartAlt,
} from "@mui/icons-material/";

import apiCreateZoo from "./api/api-create-zoo";
import apiFeedZoo from "./api/api-feed-zoo";
import apiDeleteZoo from "./api/api-delete-zoo";
import apiIncrementHourAtZoo from "./api/api-increment-hour-at-zoo";

import { HTTP_CREATED, HTTP_OK } from "./constants/http-status";
import AnimalHealthCard from "./components/animal-health-card";
import { CANT_WALK, STATUS_ORDER } from "./constants/animal-status";

function App() {
    const isMounted = useRef(true);
    const [animals, setAnimals] = useState([]);
    const [zooTime, setZooTime] = useState(null);
    const [error, setError] = useState("");

    const sortAnimalByStatus = (a, b) =>
        STATUS_ORDER[a.status] - STATUS_ORDER[b.status];

    const handleResponse = (data) => {
        const { animals: animalResponse, current_time } = data;
        setZooTime(moment(current_time, "YYYY-MM-DDTHH:mm:ss"));
        setAnimals(animalResponse.sort(sortAnimalByStatus));
    };

    useEffect(() => {
        if (isMounted.current) {
            createZoo();
        }
    }, []);

    const createZoo = async () => {
        try {
            setError("");
            const { data, status } = await apiCreateZoo();
            if (status === HTTP_CREATED) {
                handleResponse(data);
            } else {
                throw new Error("There was trouble creating the zoo");
            }
        } catch (ex) {
            setError(ex.message);
        }
    };

    const feedAnimals = async () => {
        try {
            setError("");
            const { data, status } = await apiFeedZoo();
            if (status === HTTP_OK) {
                handleResponse(data);
            } else {
                throw new Error("There was trouble feeding the zoo");
            }
        } catch (ex) {
            setError(ex.message);
        }
    };

    const incrementZooTimeByAnHour = async () => {
        try {
            setError("");
            const { data, status } = await apiIncrementHourAtZoo();
            if (status === HTTP_OK) {
                handleResponse(data);
            } else {
                throw new Error(
                    "There was trouble incrementing hour at the zoo"
                );
            }
        } catch (ex) {
            setError(ex.message);
        }
    };

    return (
        <Container maxWidth={false}>
            <Paper sx={{ padding: 2 }}>
                <Grid container spacing={2}>
                    {error && (
                        <Grid item xs={12} container justifyContent="center">
                            <Alert severity="error">{error}</Alert>
                        </Grid>
                    )}
                    <Grid item xs={12} container justifyContent="center">
                        {zooTime && (
                            <Card sx={{ padding: 2 }}>
                                <Grid container direction="column" spacing={1}>
                                    <Grid item xs={12}>
                                        <Chip
                                            size="medium"
                                            color="info"
                                            icon={<AccessTime />}
                                            label={`Zoo time: ${zooTime.format(
                                                "DD/MM/YYYY HH:MM"
                                            )}`}
                                        />
                                    </Grid>
                                    <Grid
                                        container
                                        item
                                        xs={12}
                                        justifyContent="center"
                                    >
                                        <Tooltip title="Add an hour">
                                            <IconButton
                                                aria-label="add an hour"
                                                color="error"
                                                onClick={
                                                    incrementZooTimeByAnHour
                                                }
                                            >
                                                <MoreTime />
                                            </IconButton>
                                        </Tooltip>
                                        <Tooltip title="Feed">
                                            <IconButton
                                                aria-label="feed"
                                                color="success"
                                                onClick={feedAnimals}
                                            >
                                                <LunchDining />
                                            </IconButton>
                                        </Tooltip>
                                        <Tooltip title="Reset Zoo">
                                            <IconButton
                                                aria-label="reset"
                                                color="info"
                                                onClick={createZoo}
                                            >
                                                <RestartAlt />
                                            </IconButton>
                                        </Tooltip>
                                    </Grid>
                                </Grid>
                            </Card>
                        )}
                    </Grid>
                    <Grid container item xs={12} spacing={2}>
                        {animals.map((animal) => (
                            <Grid key={animal.id} item xs={2}>
                                <AnimalHealthCard animal={animal} />
                            </Grid>
                        ))}
                    </Grid>
                </Grid>
            </Paper>
        </Container>
    );
}

export default App;
