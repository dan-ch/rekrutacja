package uwb.licencjat.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import uwb.licencjat.model.UserRole;

@Repository
public interface UserRolesRepository extends JpaRepository<UserRole, Long> {
}
