import React from "react";
import PropTypes from 'prop-types';

import { Alert } from "@userComponents/Elements/Alert";

import { WorkersItem } from "@userPages/Worker/WorkersItem";

export function WorkersList ({ data, highlight, onDelete }) {
    return <div className="list my-4">
        <div className="list-table bg-white rounded-md shadow">
            <div className="items items-workers">
                <div className="item item-header uppercase text-gray-600">
                    <div className="item-content">
                        <div className="item-infos">
                            <div className="col-1">Membre</div>
                            <div className="col-2">Email</div>
                            <div className="col-3 actions" />
                        </div>
                    </div>
                </div>

                {data.length > 0
                    ? data.map((elem) => {
                        return <WorkersItem key={elem.id} elem={elem} highlight={highlight} onDelete={onDelete} />;
                    })
                    : <Alert color="gray">Aucune donnée enregistrée.</Alert>
                }
            </div>
        </div>
    </div>
}

WorkersList.propTypes = {
    data: PropTypes.array.isRequired,
    onDelete: PropTypes.func.isRequired,
}
